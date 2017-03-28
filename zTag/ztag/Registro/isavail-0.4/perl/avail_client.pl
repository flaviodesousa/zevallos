#!/usr/local/bin/perl

#  Copyright (C) 2008 Registro.br. All rights reserved.
# 
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are
# met:
# 1. Redistribution of source code must retain the above copyright
#    notice, this list of conditions and the following disclaimer.
# 2. Redistributions in binary form must reproduce the above copyright
#    notice, this list of conditions and the following disclaimer in the
#    documentation and/or other materials provided with the distribution.
# 
# THIS SOFTWARE IS PROVIDED BY REGISTRO.BR ``AS IS AND ANY EXPRESS OR
# IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
# WARRANTIE OF FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO
# EVENT SHALL REGISTRO.BR BE LIABLE FOR ANY DIRECT, INDIRECT,
# INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
# BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS
# OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
# ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
# TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
# USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
# DAMAGE.

# $Id: avail_client.pl 64 2009-11-05 13:10:15Z eduardo $

use vars qw/ %opt /;

use Getopt::Std;
use IO::Select;
use IO::Socket::INET;
use strict;

# File where the cookie is stored
my $COOKIE_FILE = '/tmp/isavail-cookie.txt';

# Default Server Address and port
my $SERVER_ADDR = '200.160.2.3';
my $SERVER_PORT = 43;

my $MAX_UDP_SIZE = 512;
my $DEFAULT_COOKIE = '00000000000000000000';

# Maximum retries and interval
my $MAX_RETRIES = 3;
my $RETRY_TIMEOUT = 5;

package Util;
sub trim {
	my $string = shift;
	$string =~ s/^\s+//;
	$string =~ s/\s+$//;
	return $string;
}

#############################################################
##                                                         ##
##  Class responsible for parsing a Domain Check response  ##
##                                                         ##
#############################################################
package AvailResponseParser;
sub new {
    my $this = shift;
    my $class = ref($this) || $this;
    my $self = {
        status => -1,
        query_id => '',
        fqdn => '',
        fqdn_ace => '',
        expiration_date => '',
        publication_status => '',
        nameservers => '',
        tickets => '',
        release_process_dates => (),
        msg => '',
        cookie => '',
        response => '',
	suggestions => ()
    };
    return bless $self, $class;
}

sub str {
    my $self = shift;

    my $msg = '';
    $msg = "Query ID: $self->{query_id}\n";
    $msg .= "Domain name: $self->{fqdn}\n";
    $msg .= "Response Status: $self->{status} (";

    if ($self->{status} == 0) {
        $msg .= "Available)\n";

    } elsif ($self->{status} == 1) {
        $msg .= "Available with active tickets)\n";
        $msg .= "Tickets: \n";
        foreach my $t ($self->{tickets}) {
            $msg .= "  $t\n";
        }
                        
    } elsif ($self->{status} == 2) {
        $msg .= "Registered)\n";
        $msg .= 'Expiration Date: ';
        if ($self->{expiration_date} eq '0') {
            $msg .= "Exempt from payment\n";
        } else {
            $msg .= $self->{expiration_date} . "\n";
        }

        $msg .= "Publication Status: $self->{publication_status}\n";
        $msg .= "Nameservers: \n";
        foreach my $ns ($self->{nameservers}) {
            $msg .= $ns;
        }

        if (scalar(@{$self->{suggestions}}) > 0) {
	    $msg .= "Suggestions:";
	    foreach my $s (@{$self->{suggestions}}) {
		$msg .= " " . $s;
	    }
	    $msg .= "\n";
	}
            
    } elsif ($self->{status} == 3) {
        $msg .= "Unavailable)\n";
        $msg .= "Additional Message: $self->{msg}\n";

	if (scalar(@{$self->{suggestions}}) > 0) {
	    $msg .= "Suggestions:";
	    foreach my $s (@{$self->{suggestions}}) {
		$msg .= " " . $s;
	    }
	    $msg .= "\n";
	}

    } elsif ($self->{status} == 4) {
        $msg .= "Invalid query)\n";
        $msg .= "Additional Message: $self->{msg}\n";
            
    } elsif ($self->{status} == 5) {
        $msg .= "Release process waiting)\n";

    } elsif ($self->{status} == 6) {
        $msg .= "Release process in progress)\n";
        $msg .= "Release Process:\n";
        $msg .= "  Start date: " . $self->{release_process_dates}[0] . "\n";
        $msg .= "  End date:   " . $self->{release_process_dates}[1] . "\n"; 

    } elsif ($self->{status} == 7) {
        $msg .= "Release process in progress with active tickets)\n";
        $msg .= "Release Process:\n";
        $msg .= "  Start date: " . $self->{release_process_dates}[0] . "\n";
        $msg .= "  End date:   " . $self->{release_process_dates}[1] . "\n";
        $msg .= "Tickets: \n";
        foreach my $t ($self->{tickets}) {
            $msg .= $t;
        }

    } elsif ($self->{status} == 8) {
        $msg .= "Error)\n";
        $msg .= "Additional Message: $self->{msg}\n";
            
    } elsif ($self->{response} ne '') {
        $msg = $self->{response};

    } else {
        $msg = 'No response';
    }
            
    return $msg;         
}     

# Parse a string response
sub parse_response {    
    my $self = shift;
    $self->{response} = shift;

    my @buffer = split("\n", $self->{response});

    while (42) {
        last if (scalar(@buffer) == 0);
        my $line = Util::trim(shift(@buffer));

        # Ignore blank lines at the beginning
        next if (length($line) == 0);

        # Ignore comments
        next if ($line =~ /^%/);

        # Get the status of the response, or cookie
        if (($line =~ /^CK /) or ($line =~ /^ST /)) {
            my @items = split(' ', $line);

            # New cookie
            if ($items[0] eq 'CK') {
                $self->{cookie} = substr($items[1], 0, 20);
                $self->{query_id} = $items[2];
                return 0;
            }

            return -1 if (scalar(@items) == 0);

            # Get the response status
            $self->{status} = $items[1];
            
            # Status 8: Error
            if ($self->{status} == 8) {
                $self->{msg} = trim(shift(@buffer));
                return 0;
            }
            
            $self->{query_id} = $items[2];
        }

        # Get the fqdn and fqdn_ace
        $line = Util::trim(shift(@buffer));
        my @words = split('\|', $line);
        $self->{fqdn} = $words[0];
        if (scalar(@words) > 1) {
            $self->{fqdn_ace} = $words[1];
        } 
            
        # Domain available or waiting release process
        return 0 if (($self->{status} == 0) or ($self->{status} == 5));

        # Read a new line from the buffer
        $line = Util::trim(shift(@buffer));

        # Domain available with ticket: Get the list of active tickets
        if ($self->{status} == 1) {
	        my @tickets = split('\|', $line);
	        foreach my $t (@tickets) {
		        $self->{tickets} .= " $t\n";
	        }
            return 0;

        # Domain already registered
        } elsif ($self->{status} == 2) {
            @words = split('\|', $line);
            return -1 if (scalar(@words) < 2);

            $self->{expiration_date} = $words[0];
            $self->{publication_status} = $words[1];
            for (my $i = 2; $i < scalar(@words); $i++) {
                $self->{nameservers} .= "  $words[$i]\n";
            }

	    # Check if there's any suggestion
	    $line = Util::trim(shift(@buffer));
	    if ($line eq "") {
		return 0;
	    }

	    @{$self->{suggestions}} = split('\|', $line);
	    foreach my $s (@{$self->{suggestions}}) {
	        $s .= ".br";
	    }

            return 0;
 
        # Domain unavailable or invalid or release process
        } elsif ($self->{status} == 3 or $self->{status} == 4) {
            # Just get the message
            $self->{msg} = $line;

	    if ($self->{status} == 3) {
		# Check if there's any suggestion
		$line = Util::trim(shift(@buffer));
		if ($line eq "") {
		    return 0;
		}

		@{$self->{suggestions}} = split('\|', $line);
		foreach my $s (@{$self->{suggestions}}) {
		    $s .= ".br";
		}
	    }

            return 0;
 
        # Release process
        } elsif ($self->{status} == 6 or $self->{status} == 7) {
            # Get the release process dates
            my @aux = split('\|', $line);
            $self->{release_process_dates} = \@aux;
            return -1 if (scalar(@{$self->{release_process_dates}}) < 2);

            # Get the tickets (status 7)
            if ($self->{status} == 7) {
                $line = Util::trim(shift(@buffer));
                my @tickets = split('\|', $line);
                foreach my $t (@tickets) {
                    $self->{tickets} .= "  $t\n";
                }
            }
            return 0;
        }

        # Error
        return -1;
    }        
}


############################################################
##                                                        ##
## Class responsible for sending a query thru the network ##
##                                                        ##
############################################################
package AvailClient;
sub new {
    my $this = shift;
    my $atrib = shift;
    my $class = ref($this) || $this;
    my $self = {
        lang => 0,
        ip => '',
        cookie => $DEFAULT_COOKIE,
        cookie_file => $COOKIE_FILE,
        version => 1,
        server => $SERVER_ADDR,
        port => $SERVER_PORT,
	suggest => 1
    };

    foreach my $key (keys %{$atrib}) {
        $self->{$key} = $atrib->{$key};
    }

    my $return = bless $self, $class;

    if (not open(COOKIE, "<$self->{cookie_file}")) {
        # Send a query with an invalid cookie
        $self->send_query('registro.br');
    } else {
        $self->{cookie} = <COOKIE>;
        close(COOKIE);
    }

    return $return;
}

sub send_query {
    my $self = shift;
    my $fqdn = shift;

    my $query = '';
    if ($self->{ip} ne '') {
        $query .= "[$self->{ip}] ";
    }

    # Create a random 10 digit query ID (2^32)
    my $query_id .= int(rand(4294967296));

    # Form the query
    $query .= $self->{version}.' '.$self->{cookie}.' '.
              $self->{lang}.' '.$query_id.' '.Util::trim($fqdn);

    if ($self->{version} > 0) {
	$query .= " " . $self->{suggest};
    }

    # Create a new socket
    my $sock = new IO::Socket::INET->new(PeerPort=>$self->{port},
        Proto=>'udp', PeerAddr=>$self->{server}, Timeout=>$RETRY_TIMEOUT);

    # Send the query and wait for a response
    my $timeout = 0;
    my $retries = 0;
    my $resend = 'true';

    # Response parser
    my $parser = new AvailResponseParser();
    while (42) {
        # Check the need to (re)send the query
        if ($resend eq 'true') {
            $resend = 'false';
            $retries++;
            if ($retries > $MAX_RETRIES) {
                last;
            }
            
            # Send the query
            $sock->send($query);
        }
        
        my $response = '';
        my $sel = new IO::Select($sock);
        my @ready = $sel->can_read($timeout + $RETRY_TIMEOUT); 
        if((scalar(@ready) > 0) and ($ready[0] == $sock)) {
            $sock->recv($response, $MAX_UDP_SIZE);
        } else {
            $timeout += $RETRY_TIMEOUT;
            $resend = 'true';
            next;
        }

        # Response received. Call the parser
        $parser->parse_response($response);

        # Check the query ID
        if (($parser->{query_id} ne $query_id) and
            ($parser->{status} != 8)) {
            # Wrong query ID. Just wait for another response
            $resend = 'false';
            next;
        }            
        
        # Check if the cookie was invalid
        if ($parser->{cookie} ne '') {
            # Save the new cookie
            my $cookie = $self->{cookie};
            $self->{cookie} = $parser->{cookie};

            if (open(COOKIE, ">$self->{cookie_file}")) {
                print COOKIE $self->{cookie};
                close(COOKIE);
            }

            if ($cookie eq $DEFAULT_COOKIE) {
                # Nothing else to do
                last;
            } else {
                # Resend query. Now we should have the right cookie
                $parser = $self->send_query($fqdn);
                last;
            }

        }
        last;
    }        
    
    # Return the filled ResponseParser object
    return $parser;
}

#############################################################
##                                                         ##
##                Command-line client                      ##
##                                                         ##
#############################################################
package main;
sub usage {
    print "\n";
    print "Usage:\n";
    print "\t./avail_client.pl [-h] [-d] [-l language] [-s server_IP]\n";
    print "\t                  [-p server_port] [-c cookie_file] \n";
    print "\t                  [-a proxied_IP] [-S] fqdn\n\n";
    print "\t-h Print this help\n";
    print "\t-d Turn ON debug mode\n";
    print "\t-l language: EN or PT (Default: PT)\n";
    print "\t-s server_IP: Server's IP address (Default: $SERVER_ADDR )\n";
    print "\t-p server_port: Server's port number (Default: $SERVER_PORT )\n";
    print "\t-c cookie_file: File where the cookie is stored\n";
    print "\t   (Default: $COOKIE_FILE )\n";
    print "\t-a proxied_IP: Client IP address being proxied\n";
    print "\t-S Enable suggestion in server answer";
    print "\tfqdn: fully qualified domain name being queried\n";
    print "\n";
}

# Get the command line options
my $opt_string = 'dl:s:p:c:a:hS';
getopts( "$opt_string", \%opt ) or usage();

if ($opt{h}) {
  usage();
  exit;
}

# Default parameters
my $debug = ($opt{d}) ? 'true' : 'false';

my $atrib = ();
$atrib->{lang}        = (uc($opt{l}) eq 'PT') ? 0 : 1;
$atrib->{server}      = ($opt{s}) ? $opt{s} : $SERVER_ADDR;
$atrib->{port}        = ($opt{p}) ? $opt{p} : $SERVER_PORT;
$atrib->{cookie_file} = $COOKIE_FILE;
$atrib->{ip}          = ($opt{a}) ? $opt{p} : '';
$atrib->{suggest}     = ($opt{S}) ? 1 : 0;

# There must be at least one argument (FQDN)
if (scalar(@ARGV) <= 0) {
    usage();
    exit;
} 

# Get the option values
my $fqdn = $ARGV[$#ARGV];

# Initialize client object and send query
my $ac = new AvailClient($atrib);
my $arp = $ac->send_query($fqdn);
print $arp->str()."\n";

if ($debug eq 'true') {
    print "*****Response received*****\n";
    print "$arp->{response}\n";
}

exit;
