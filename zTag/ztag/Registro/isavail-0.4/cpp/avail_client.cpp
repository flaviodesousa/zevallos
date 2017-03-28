//  Copyright (C) 2008 Registro.br. All rights reserved.
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

// $Id: avail_client.cpp 64 2009-11-05 13:10:15Z eduardo $

#include <fstream>
#include <limits>
#include <netinet/in.h>
#include <sstream>
#include <stdlib.h>
#include <string.h>
#include <sys/socket.h>
#include <time.h>
#include <unistd.h>
#include <vector>

using std::endl;
using std::ifstream;
using std::numeric_limits;
using std::ofstream;
using std::string;
using std::stringstream;
using std::vector;

// GETOPT
extern char *optarg;
extern int optind;
extern int optopt;
extern int opterr;
extern int optreset;

// File where the cookie is stored
#define COOKIE_FILE "/tmp/isavail-cookie.txt"

// Default server address and port
#define SERVER_ADDR "200.160.2.3"
#define SERVER_PORT 43

#define MAX_UDP_SIZE 512
#define DEFAULT_COOKIE "00000000000000000000"

// Maximum retries and interval
#define MAX_RETRIES 3
#define RETRY_TIMEOUT 5

/////////////////////////////////////////////////////////////////
////                                                         ////
////                     String Utils                        ////
////                                                         ////
/////////////////////////////////////////////////////////////////

class StringUtils
{
public:
  // Return a copy of the string with leading and trailing characters
  // removed
  static string strip(const string &input)
  {
    string output = input;

    for (unsigned int i = 0; i < output.length(); i++) {
      // Remove special chars
      if (output[i] == ' ' || output[i] == '\n' || 
	  output[i] == '\t' || output[i] == '\r' || 
	  output[i] == '\v' || output[i] == '\f') {
	output.replace(i, 1, " ");
      }
    }

    // Trim
    string::size_type pos = output.find_last_not_of(' ');
    if(pos != string::npos) {
      output.erase(pos + 1);
      pos = output.find_first_not_of(' ');
      if(pos != string::npos) {
	output.erase(0, pos);
      }
    } else {
      // Blank string
      output.erase(output.begin(), output.end());
    }

    return output;
  }

  // Return a list of the words of the string input. the words are
  // separated by separator char.
  static vector<string> split(const string &input, const char &separator = ' ')
  {
    vector<string> output;
    int sepIndex = 0;
    int lastIndex = 0;
    
    while (true) {
      sepIndex = input.find(separator, sepIndex);
      if (sepIndex == -1) {
	output.push_back(input.substr(lastIndex, input.length() - lastIndex));
	break;
      }

      output.push_back(input.substr(lastIndex, sepIndex - lastIndex));

      sepIndex++;
      lastIndex = sepIndex;

      if (sepIndex >= (int) input.length()) {
	break;
      }
    }
    
    return output;
  }

  // Change each element of the string to upper case
  static string toUpper(const string &input)
  {
    string output = "";
    for(unsigned int i = 0; i < input.length(); i++) {
      output += toupper(input[i]);
    }
    
    return output;
  }
};

/////////////////////////////////////////////////////////////////
////                                                         ////
////  Class responsible for parsing a Domain Check response  ////
////                                                         ////
/////////////////////////////////////////////////////////////////

class AvailResponseParser
{
public:
  AvailResponseParser() :
    _status(-1),
    _query_id(""),
    _fqdn(""),
    _fqdn_ace(""),
    _expiration_date(""),
    _publication_status(""),
    _msg(""),
    _cookie(""),
    _response("")
  {
    _nameservers.clear();
    _tickets.clear();
    _release_process_dates.clear();
    _suggestions.clear();
  }
  

  string toString()
  {
    string msg = "Query ID: " + _query_id + "\n";
    msg += "Domain name: " + _fqdn + "\n";

    stringstream status;
    status << _status;
    msg += "Response Status: " + status.str() + " (";
    if (_status == 0) {
      msg += "Available)\n";
    } else if (_status == 1) {
      msg += "Available with active tickets)\n";

      msg += "Tickets: \n";
      vector<int>::const_iterator tktIt;
      for (tktIt = _tickets.begin(); tktIt != _tickets.end(); tktIt++) {
	stringstream ticket;
	ticket << *tktIt;
	msg += " " + ticket.str() + "\n";
      }
    } else if (_status == 2) {
      msg += "Registered)\n";
      msg += "Expiration Date: ";
      if (_expiration_date == "0") {
	msg += "Exempt from payment\n";
      } else {
	msg += _expiration_date + "\n";
      }
      msg += "Publication Status: " + _publication_status + "\n";

      msg += "Nameservers: \n";
      vector<string>::const_iterator nsIt;
      for (nsIt = _nameservers.begin(); nsIt != _nameservers.end(); nsIt++) {
	msg += " " + *nsIt + "\n";
      }

      if (_suggestions.size() > 0) {
	msg += "Suggestions:";

	vector<string>::const_iterator sugIt;
	for (sugIt = _suggestions.begin(); 
	     sugIt != _suggestions.end(); sugIt++) {
	  msg += " " + *sugIt;
	}
	
	msg += "\n";
      }
    } else if (_status == 3) {
      msg += "Unavailable)\n";
      msg += "Additional Message: " + _msg + "\n";
      
      if (_suggestions.size() > 0) {
	msg += "Suggestions:";
	
	vector<string>::const_iterator sugIt;
	for (sugIt = _suggestions.begin(); 
	     sugIt != _suggestions.end(); sugIt++) {
	  msg += " " + *sugIt;
	}
	
	msg += "\n";
      }
    } else if (_status == 4) {
      msg += "Invalid query)\n";
      msg += "Additional Message: " + _msg + "\n";
    } else if (_status == 5) {
      msg += "Release process waiting)\n";
    } else if (_status == 6) {
      msg += "Release process in progress)\n";
      if (_release_process_dates.size() == 2) {
	msg += "Release Process:\n";
	msg += "  Start date: " + _release_process_dates[0] + "\n";
	msg += "  End date:   " + _release_process_dates[1] + "\n";
      }
    } else if (_status == 7) {
      msg += "Release process in progress with active tickets)\n";
      if (_release_process_dates.size() == 2) {
	msg += "Release Process:\n";
	msg += "  Start date: " + _release_process_dates[0] + "\n";
	msg += "  End date:   " + _release_process_dates[1] + "\n";
      }
      
      msg += "Tickets: \n";
      vector<int>::const_iterator tktIt;
      for (tktIt = _tickets.begin(); tktIt != _tickets.end(); tktIt++) {
	stringstream ticket;
	ticket << *tktIt;
	msg += " " + ticket.str() + "\n";
      }
    } else if (_status == 8) {
      msg += "Error)\n";
      msg += "Additional Message: " + _msg + "\n";
    } else if (_response != "") {
      msg = _response;
    } else {
      msg = "No response";
    }

    return msg;
  }

  int parse_response(const string &response) 
  {
    _response = response;
    stringstream buffer(response);

    string line("");
    while(true) {
      if (buffer.good() == false) return -1;
      getline(buffer, line);
      line = StringUtils::strip(line);

      // Ignore blank lines at the beginning
      if (line == "") {
	continue;
      }

      // Ignore comments
      if (line.substr(0, 1) == "%") {
	continue;
      }

      if (line.length() < 3) {
	return -1;
      }

      // Get the status of the response, or cookie
      if (line.substr(0, 3) == "CK " || line.substr(0, 3) == "ST ") {
	vector<string> items = StringUtils::split(line);

	// New cookie
	if (items[0] == "CK") {
	  if (items.size() != 3) {
	    return -1;
	  }

	  _cookie = items[1].substr(0, 20);
	  _query_id = items[2];
	  return 0;
	}

	if (items.size() < 2) {
	  return -1;
	}

	// Get the response status
	try {
	  _status = atoi(items[1].c_str());
	} catch ( ... ) {
	  return -1;
	}

	// Status 8: Error
	if (_status == 8) {
	  if (buffer.good() == false) return -1;
	  getline(buffer, line);
	  line = StringUtils::strip(line);
	  _msg = line;
	  return 0;
	}

	if (items.size() < 3) {
	  return -1;
	}
	
	_query_id = items[2];
      }

      // Get the fqdn and fqdn_ace
      if (buffer.good() == false) return -1;
      getline(buffer, line);
      line = StringUtils::strip(line);
      vector<string> words = StringUtils::split(line, '|');
      if (words.size() == 1) {
	_fqdn = words[0];
      } else if (words.size() == 2) {
	_fqdn_ace = words[1];
	_fqdn = words[0];
      } else {
	return -1;
      }

      if (_status == 0 || _status == 5) {
	// Domain available or waiting release process
	return 0;
      }

      // Read a new line from the buffer
      if (buffer.good() == false) return -1;
      getline(buffer, line);
      line = StringUtils::strip(line);

      // Domain available with ticket: Get the list of active tickets
      if (_status == 1) {
	vector<string> tickets = StringUtils::split(line, '|');
	vector<string>::const_iterator tktIt;
	for (tktIt = tickets.begin(); tktIt != tickets.end(); tktIt++) {
	  _tickets.push_back(atoi(tktIt->c_str()));
	}

	return 0;

      // Domain already registered
      } else if (_status == 2) {
	words = StringUtils::split(line, '|');
	if (words.size() < 2) {
	  return -1;
	}

	_expiration_date = words[0];
	_publication_status = words[1];
	
	for (unsigned int i = 2; i < words.size(); i++) {
	  _nameservers.push_back(words[i]);
	}

	// Check if there's any suggestion
	if (buffer.good() == false) return 0;
	getline(buffer, line);
	line = StringUtils::strip(line);
	if (line == "") {
	  return 0;
	}

	vector<string> suggestions = StringUtils::split(line, '|');
	vector<string>::const_iterator sugIt;
	for (sugIt = suggestions.begin(); sugIt != suggestions.end(); sugIt++) {
	  _suggestions.push_back(*sugIt + ".br");
	}

	return 0;

      // Domain unavailable or invalid
      } else if (_status == 3 || _status == 4) {
	// Just get the message
	_msg = line;

	if (_status == 4) {
	  return 0;
	}

	// Check if there's any suggestion
	if (buffer.good() == false) return 0;
	getline(buffer, line);
	line = StringUtils::strip(line);
	if (line == "") {
	  return 0;
	}

	vector<string> suggestions = StringUtils::split(line, '|');
	vector<string>::const_iterator sugIt;
	for (sugIt = suggestions.begin(); sugIt != suggestions.end(); sugIt++) {
	  _suggestions.push_back(*sugIt + ".br");
	}

	return 0;

      // Release process
      } else if (_status == 6 || _status == 7) {
	// Get the release process dates
	_release_process_dates = StringUtils::split(line, '|');
	if (_release_process_dates.size() < 2) {
	  return -1;
	}

	if (_status == 6) {
	  return 0;
	}

	// Get the tickets (status 7)
	if (buffer.good() == false) return 0;
	getline(buffer, line);
	line = StringUtils::strip(line);
	
	vector<string> tickets = StringUtils::split(line, '|');
	vector<string>::const_iterator tktIt;
	for (tktIt = tickets.begin(); tktIt != tickets.end(); tktIt++) {
	  _tickets.push_back(atoi(tktIt->c_str()));
	}

	return 0;
      }

      // Error
      return -1;
    }
    
    // Error
    return -1;
  }

  int get_status() const
  {
    return _status;
  }

  string get_query_id() const
  {
    return _query_id;
  }

  string get_cookie() const
  {
    return _cookie;
  }

  string get_response() const
  {
    return _response;
  }

private:
  int _status;
  string _query_id;
  string _fqdn;
  string _fqdn_ace;
  string _expiration_date;
  string _publication_status;
  vector<string> _nameservers;
  vector<int> _tickets;
  vector<string> _release_process_dates;
  string _msg;
  string _cookie;
  string _response;
  vector<string> _suggestions;
};

////////////////////////////////////////////////////////////////
////                                                        ////
//// Class responsible for sending a query thru the network ////
////                                                        ////
////////////////////////////////////////////////////////////////

class AvailClient
{
public:
  AvailClient(const int &lang,
	      const string &ip,
	      const string &cookie_file,
	      const int &version,
	      const string &server,
	      const int &port,
	      const int &suggest = 1) :
    _lang(0),
    _ip(""),
    _cookie(""),
    _cookie_file(""),
    _version(0),
    _server(""),
    _port(0),
    _suggest(0)
  {
    _lang = lang;
    _ip = ip;
    _cookie = DEFAULT_COOKIE;
    _cookie_file = cookie_file;
    _version = version;
    _server = server;
    _port = port;
    _suggest = suggest;

    // Try to get cookie from file
    // If can't open file, send an invalid-cookie query
    ifstream file(_cookie_file.c_str());
    if (file.good() == true) {
      string line;
      getline(file, line);
      _cookie = line;
      file.close();
      
      if (_cookie.substr(_cookie.length() - 1, 1) == "\n") {
	_cookie = _cookie.substr(0, _cookie.length() - 1);
      }
    } else {
      send_query("registro.br");
    }
  }

  AvailResponseParser send_query(const string &fqdn) {

    // Response parser
    AvailResponseParser parser;

    string query = "";
    if (_ip != "") {
      query += "[" + _ip + "] ";
    }

    // Create a random 10 digit query ID (2^32)
    stringstream query_id;
    srandom(time(NULL));
    query_id << (int) (random() % numeric_limits<int>::max());
    
    // Form the query
    stringstream version;
    stringstream lang;

    version << _version;
    lang << _lang;
    
    query += version.str() + " " + _cookie + " " + lang.str() + " " + 
      query_id.str() + " " + StringUtils::strip(fqdn);

    if (_version > 0) {
      stringstream suggest;
      suggest << _suggest;
      query += " " + suggest.str();
    }

    // Only IPv4 for now
    int sd;
    if ((sd = socket(AF_INET, SOCK_DGRAM, 0)) < 0) {
      return parser;
    }

    struct sockaddr_in server;
    server.sin_family = AF_INET;
    server.sin_port = htons(_port);
    uint32_t server_addr = htonl(ipv4ToWire(_server));
    memcpy((char *) &server.sin_addr.s_addr, &server_addr, 4);
    
    // Send the query and wait for a response
    int timeout = 0;
    int retries = 0;
    bool resend = true;

    while(true) {
      // Check the need to (re)send the query
      if (resend == true) {
	resend = false;
	retries++;
	if (retries > MAX_RETRIES) {
	  break;
	}

	if (sendto(sd, query.c_str(), query.length(), 0, 
		   (struct sockaddr *) &server, sizeof(struct sockaddr)) < 0) {
	  break;
	}
      }

      // Set the timeout
      timeout += RETRY_TIMEOUT;
      setsockopt(sd, SOL_SOCKET, SO_LINGER, 
		 &timeout, numeric_limits<int>::max());

      char response[MAX_UDP_SIZE];
      int responseSize;
      if ((responseSize = recv(sd, &response, MAX_UDP_SIZE, 0)) < 0) {
	// Timeout: Resend the query and wait a little longer
	resend = true;
	continue;
      }
      response[responseSize] = '\0';

      // Response received. Call the parser
      parser.parse_response(string(response));

      // Check the query ID
      if (parser.get_query_id() != query_id.str() && 
	  parser.get_status() != 8) {
	// Wrong query ID. Just wait for another response
	resend = false;
	continue;
      }

      // Check if the cookie was invalid
      if (parser.get_cookie() == "") {
	break;
      }

      // Save the new cookie
      string cookie = _cookie;
      _cookie = parser.get_cookie();

      ofstream file(_cookie_file.c_str());
      if (file.good() == true) {
	file << _cookie << endl;
	file.close();
      }

      if (cookie == DEFAULT_COOKIE) {
	// Nothing else to do
	break;
      } else {
	// Resend query. Now we should have the right cookie
	parser = send_query(fqdn);
	break;
      }

      break;
    }
    
    close(sd);

    // Return the filled ResponseParser object
    return parser;
  }

private:
  uint32_t ipv4ToWire(const string &ip)
  {
    vector<string> ipOctets = StringUtils::split(ip, '.');
    vector<string>::iterator it;
    
    uint32_t outputIp = 0;
    int initalPos = 0;
    
    for (it = ipOctets.begin(); it != ipOctets.end(); it++) {
      uint8_t octet = atoi((*it).c_str());
      initalPos = initalPos + 8;
      outputIp |= (octet << (32 - initalPos));
    }
    
    return outputIp;
  }

  int _lang;
  string _ip;
  string _cookie;
  string _cookie_file;
  int _version;
  string _server;
  int _port;
  int _suggest;
};

/////////////////////////////////////////////////////////////////
////                                                         ////
////                Command-line client                      ////
////                                                         ////
/////////////////////////////////////////////////////////////////

void usage()
{
  printf ("\n");
  printf ("Usage:\n");
  printf ("\t./avail_client [-h] [-d] [-l language] [-s server_IP]\n");
  printf ("\t                  [-p server_port] [-c cookie_file]\n");
  printf ("\t                  [-a proxied_IP] [-S] fqdn\n\n");
  printf ("\t-h Print this help\n");
  printf ("\t-d Turn ON debug mode\n");
  printf ("\t-l language: EN or PT (Default: PT)\n");
  printf ("\t-s server_IP: Server's IP address (Default: %s)\n", SERVER_ADDR);
  printf ("\t-p server_port: Server's port number (Default: %d)\n", SERVER_PORT);
  printf ("\t-c cookie_file: File where the cookie is stored\n");
  printf ("\t   (Default: %s)\n", COOKIE_FILE);
  printf ("\t-a proxied_IP: Client IP address being proxied\n");
  printf ("\t-S Enable suggestion in server answer\n");
  printf ("\tfqdn: fully qualified domain name being queried\n");
  printf("\n");
}

int main(int argc, char **argv)
{
  // Default parameters
  bool debug = false;
  int language = 1;
  string server_addr = SERVER_ADDR;
  int server_port = SERVER_PORT;
  string cookie_f = COOKIE_FILE;
  string proxied = "";
  int sug = 0;

  // Get the option values
  int opt;
  while ((opt = getopt(argc, argv, "hdl:s:p:c:a:S")) != -1) {
    switch(opt) {
    case 'd':
      debug = true;
      break;
    case 'h':
      usage();
      exit(0);
      break;
    case 'l':
      if (StringUtils::toUpper(optarg) == "EN") {
	language = 0;
      } else if (StringUtils::toUpper(optarg) == "PT") {
	language = 1;
      } else {
	// Just leave de default language
      }
      break;
    case 'a':
      proxied = optarg;
      break;
    case 's':
      server_addr = optarg;
      break;
    case 'c':
      cookie_f = optarg;
      break;
    case 'p':
      server_port = atoi(optarg);
      break;
    case 'S':
      sug = 1;
      break;
    default:
      usage();
      exit(1);
    }
  }

  argc -= optind;
  argv += optind;
  
  // There must be at least one argument (FQDN)
  if (argc == 0) {
    usage();
    exit(1);
  }
  
  string fqdn = argv[0];
  
  AvailClient ac(language, proxied, cookie_f, 1, 
		 server_addr, server_port, sug);
  
  AvailResponseParser arp = ac.send_query(fqdn);
  
  printf("%s\n", arp.toString().c_str());
  if (debug == true) {
    printf("*****Response received*****\n");
    printf("%s\n", arp.get_response().c_str());
  }
  
  return 0;
}
