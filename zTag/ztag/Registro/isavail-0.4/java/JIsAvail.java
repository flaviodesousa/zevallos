// $Id: JIsAvail.java 58 2008-09-11 19:34:51Z rafael $
/* 
 * Copyright (C) 2008 Registro.br. All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 * 1. Redistribution of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 
 * THIS SOFTWARE IS PROVIDED BY REGISTRO.BR ``AS IS AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIE OF FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO
 * EVENT SHALL REGISTRO.BR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS
 * OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
 * TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
 * USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 */

import br.nic.isavail.*;

import java.io.IOException;
import java.net.SocketException;
import java.net.UnknownHostException;

/** Command-line client */
public class JIsAvail
{
  /** Prints usage information
   * @return no return value
   */
  private static void usage()
  {
    System.out.println();
    System.out.println("Usage:");
    System.out.println("\tjava -jar JIsAvail.jar [-h] [-d] [-l language] " +
		       "[-s server_IP]");
    System.out.println("\t                       [-p server_port] [-c cookie_file] ");
    System.out.println("\t                       [-a proxied_IP] [-S] <fqdn>\n");
    System.out.println("\t-h Print this help");
    System.out.println("\t-d Turn ON debug mode");
    System.out.println("\t-l language: EN or PT (Default: PT)");
    System.out.println("\t-s server_IP: Server's IP address (Default: " +
		       IsAvailDefs.SERVER_ADDR + ")");
    System.out.println("\t-p server_port: Server's port number (Default: " +
		       IsAvailDefs.SERVER_PORT + ")");
    System.out.println("\t-c cookie_file: File where the cookie is stored");
    System.out.println("\t   (Default: " + IsAvailDefs.COOKIE_FILE + ")");
    System.out.println("\t-a proxied_IP: Client IP address being proxied");
    System.out.println("\t-S Enable suggestion in server answer");
    System.out.println("\tfqdn: fully-qualified domain name to be queried");
    System.out.println();
  }

  /** Sample isavail client application main method
   * @param args array of string arguments
   * @return     no return value
   */
  public static void main(String[] args)
    throws SocketException, UnknownHostException, IOException
  {
    // There must be at least one argument (FQDN)
    if (args.length == 0) {
      usage();
      return;
    }
    String fqdn = args[args.length - 1];

    // Default parameters
    int version = 1;
    boolean debug = false;
    int language = 1; // PT
    String server_addr = IsAvailDefs.SERVER_ADDR;
    int server_port = IsAvailDefs.SERVER_PORT;
    String cookie_f = IsAvailDefs.COOKIE_FILE;
    String proxied = "";
    boolean suggest = false;

    // Get the option values
    for (int i = 0; i < args.length - 1; i++) {
      if (args[i].equals("-d")) {
	debug = true;
      } else if (args[i].equals("-h")) {
	usage();
	return;
      } else if (args[i].equals("-l")) {
	if (++i < args.length - 1) {
	  if (args[i].equals("EN")) {
	    language = 0;
	  }
	}
      } else if (args[i].equals("-a")) {
	if (++i < args.length - 1) {
	  proxied = args[i];
	}
      } else if (args[i].equals("-s")) {
	if (++i < args.length - 1) {
	  server_addr = args[i];
	}
      } else if (args[i].equals("-c")) {
	if (++i < args.length - 1) {
	  cookie_f = args[i];
	}
      } else if (args[i].equals("-p")) {
	if (++i < args.length - 1) {
	  server_port = Integer.parseInt(args[i]);
	}
      } else if (args[i].equals("-S")) {
	suggest = true;
      } else {
	System.out.println("Unknown option: " + args[i]);
	usage();
	return;
      }
    }

    // Initialize client object and send query
    AvailClient ac = new AvailClient(language,
				     proxied,
				     cookie_f,
				     version,
				     server_addr,
				     server_port,
				     suggest);
    AvailResponseParser arp = ac.sendQuery(fqdn);

    System.out.println(arp);
    if (debug == true) {
      System.out.println("*****Response received*****");
      System.out.println(arp.getResponse());
    }
  }
}
