// $Id: AvailClient.java 58 2008-09-11 19:34:51Z rafael $
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

package br.nic.isavail;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringReader;

import java.net.DatagramPacket;
import java.net.DatagramSocket;
import java.net.InetAddress;
import java.net.SocketException;
import java.net.SocketTimeoutException;
import java.net.UnknownHostException;

import java.util.ArrayList;
import java.util.Random;

/** Class responsible for sending a query thru the network */
public class AvailClient {
  private int _lang = 0;
  private String _ip = "";
  private String _cookie = IsAvailDefs.DEFAULT_COOKIE;
  private String _cookie_file = IsAvailDefs.COOKIE_FILE;
  private int _version = 1;
  private String _server = IsAvailDefs.SERVER_ADDR;
  private int _port = IsAvailDefs.SERVER_PORT;
  private boolean _suggest = true;

  /** Constructor
   * @param lang        desired output language (0 for EN, 1 for PT)
   * @param ip          proxy IP address (empty for no proxy)
   * @param cookie_file path to store cookie file
   * @param version     isavail protocol version
   * @param server      server host name or IP address
   * @param port        server port
   * @param suggest     enable server domain suggestions
   * @throws java.net.SocketException
   * @throws java.net.UnknownHostException
   * @throws java.io.IOException
   */
  public AvailClient(int lang,
		     String ip,
		     String cookie_file,
		     int version,
		     String server,
		     int port,
		     boolean suggest)
    throws SocketException, UnknownHostException, IOException
  {
    _lang = lang;
    _ip = ip;
    _cookie_file = cookie_file;
    _version = version;
    _server = server;
    _port = port;
    _suggest = suggest;

    // Try to get cookie from file
    // If can't open file, send an invalid-cookie query
    try {
      BufferedReader f = new BufferedReader(new FileReader(_cookie_file));

      _cookie = f.readLine().trim();
      f.close();
    } catch (IOException e) {
      sendQuery("registro.br");
    }
  }

  /** Sends a query
   * @param fqdn fully-qualified domain name to be checked
   * @return     no return value
   * @throws java.net.SocketException
   * @throws java.net.UnknownHostException
   * @throws java.io.IOException
   */
  public AvailResponseParser sendQuery(String fqdn)
    throws SocketException, UnknownHostException, IOException
  {
    String query = "";
    if (_ip != "") {
      query += "[" + _ip +  "]";
    }

    // Create a random query ID
    String query_id =
      Integer.toString(new Random().nextInt(IsAvailDefs.MAX_INT));

    // Form the query
    query += _version + " " + _cookie + " " + _lang + " " + query_id + " " +
      fqdn.trim();

    if (_version > 0) {
      if (_suggest == true) {
	query += " 1";
      } else {
	query += " 0";
      }
    }

    DatagramSocket sock = new DatagramSocket();
    InetAddress addr = InetAddress.getByName(_server);

    DatagramPacket packet =
      new DatagramPacket(new byte[IsAvailDefs.MAX_UDP_SIZE],
			 IsAvailDefs.MAX_UDP_SIZE);

    // Send the query and wait for a response
    int timeout = 0;
    int retries = 0;
    boolean resend = true;

    // Response parser
    AvailResponseParser parser = new AvailResponseParser();
    while (true) {
      // Check the need to (re)send the query
      if (resend == true) {
	resend = false;
	retries += 1;
	if (retries > IsAvailDefs.MAX_RETRIES) {
	  break;
	}

	// Send the query
	packet = new DatagramPacket(query.getBytes(), query.getBytes().length,
				    addr, _port);
	sock.send(packet);
      }

      // Set the timeout
      timeout += IsAvailDefs.RETRY_TIMEOUT;
      sock.setSoTimeout(1000 * timeout);

      String response;
      byte[] message = new byte[IsAvailDefs.MAX_UDP_SIZE];
      packet.setData(message);
      try {
	sock.receive(packet);
	response = new String(packet.getData());
      } catch (SocketTimeoutException ex) {
	// Timeout: Resend the query and wait a little longer
	resend = true;
	continue;
      }

      // Response received. Call the parser
      parser.parseResponse(response);

      // Check the query ID
      if (!parser.getQueryID().equals(query_id) &&
	  parser.getStatus() != 8) {
	// Wrong query ID. Just wait for another response
	resend = false;
	continue;
      }

      // Check if the cookie was invalid
      if (!parser.getCookie().equals("")) {

	// Save the new cookie
	String cookie = _cookie;
	_cookie = parser.getCookie();

	try {
	  PrintWriter f =
	    new PrintWriter(new BufferedWriter(new FileWriter(_cookie_file)));
	  f.print(_cookie);
	  f.close();
	} catch (IOException ex) {
	  // do nothing
	}

	if (cookie == IsAvailDefs.DEFAULT_COOKIE) {
	  // Nothing else to do
	  break;
	} else {
	  // Resend query. Now we should have the right cookie
	  parser = sendQuery(fqdn);
	  break;
	}
      }
      break;
    }
    return parser;
  }
}
