// $Id: AvailResponseParser.java 58 2008-09-11 19:34:51Z rafael $
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

import java.util.ArrayList;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.StringReader;

import java.util.Iterator;

/** Class responsible for parsing a domain query response */
public class AvailResponseParser
{
  private int _status = -1;
  private String _query_id = "";
  private String _fqdn = "";
  private String _fqdn_ace = "";
  private String _expiration_date = "";
  private String _publication_status = "";
  private ArrayList _nameservers = new ArrayList();
  private ArrayList _tickets = new ArrayList();
  private String[] _release_process_dates;
  private String _msg = "";
  private String _cookie = "";
  private String _response = "";
  private ArrayList _suggestions = new ArrayList();

  /** Getter for query ID
   * @return query ID
   */
  public String getQueryID()
  {
    return _query_id;
  }

  /** Getter for status
   * @return status
   */
  public int getStatus()
  {
    return _status;
  }

  /** Getter for cookie
   * @return cookie
   */
  public String getCookie()
  {
    return _cookie;
  }

  /** Getter for raw response
   * @return response
   */
  public String getResponse()
  {
    return _response;
  }

  /** Default print format
   * @return response formatted as String
   */
  public String toString()
  {
    String msg = "Query ID: " + _query_id + "\n";
    msg += "Domain name: " + _fqdn + "\n";
    msg += "Response Status: " + _status + " (";

    Iterator it;

    switch (_status) {
    case 0:
      msg += "Available)\n";
      break;

    case 1:
      msg += "Available with active tickets)\n";
      msg += "Tickets: \n";
      it = _tickets.iterator();
      while (it.hasNext()) {
	msg += "  " + ((Integer)it.next()) + "\n";
      }
      break;

    case 2:
      msg += "Registered)\n";
      msg += "Expiration Date: ";
      if (_expiration_date.equals("0")) {
	msg += "Exempt from payment\n";
      } else {
	msg += _expiration_date + "\n";
      }
      msg += "Publication Status: " + _publication_status + "\n";
      msg += "Nameservers:\n";
      it = _nameservers.iterator();
      while (it.hasNext()) {
	msg += "  " + ((String)it.next()) + "\n";
      }

      if (_suggestions.size() > 0) {
	msg += "Suggestions:";
	it = _suggestions.iterator();
	while (it.hasNext()) {
	  msg += " " + ((String)it.next());
	}
	msg += "\n";
      }

      break;

    case 3:
      msg += "Unavailable)\n";
      msg += "Additional Message: " + _msg + "\n";

      if (_suggestions.size() > 0) {
	msg += "Suggestions:";
	it = _suggestions.iterator();
	while (it.hasNext()) {
	  msg += " " + ((String)it.next());
	}
	msg += "\n";
      }

      break;

    case 4:
      msg += "Invalid query)\n";
      msg += "Additional Message: " + _msg + "\n";
      break;

    case 5:
      msg += "Release process waiting)\n";
      break;

    case 6:
      msg += "Release process in progress)\n";
      msg += "Release Process:\n";
      msg += "  Start date: " + _release_process_dates[0] + "\n";
      msg += "  End date:   " + _release_process_dates[1] + "\n";
      break;
            
    case 7:
      msg += "Release process in progress with active tickets)\n";
      msg += "Release Process:\n";
      msg += "  Start date: " + _release_process_dates[0] + "\n";
      msg += "  End date:   " + _release_process_dates[1] + "\n";
      msg += "Tickets: \n";
      it = _tickets.iterator();
      while (it.hasNext()) {
	msg += "  " + ((Integer)it.next()) + "\n";
      }
      break;

    case 8:
      msg += "Error)\n";
      msg += "Additional Message: " + _msg + "\n";
      break;     

    default:
      if (_response != "") {
	msg = _response;
      } else {
	msg = "No response";
      }
    }

    return msg;
  }

  /** Parse a response String received from server and fills private attributes
   * @param response response String received from server
   * @return         no return value
   * @throws java.io.IOException
   */
  public void parseResponse(String response) throws IOException
  {
    _response = response;
    BufferedReader buffer = new BufferedReader(new StringReader(response));

    String line = "";
    while (true) {
      line = buffer.readLine();
      if (line == null) {
	return;
      }
      line = line.trim();

      // Ignore blank lines at the beginning
      if (line == "") {
	continue;
      }

      // Ignore comments
      if (line.startsWith("%")) {
	continue;
      }

      // Get the status of the response, or cookie
      if (line.startsWith("CK ") || line.startsWith("ST ")) {
	String[] items = line.split("[ ]+");

	// New cookie
	if (items[0].equals("CK")) {
	  _cookie = items[1];
	  _query_id = items[2];
	  return;
	}

	// Get the response status
	_status = Integer.parseInt(items[1]);

	// Status 8: Error
	if (_status == 8) {
	  _msg = buffer.readLine();
	  if (_msg == null) {
	    return;
	  }
	  _msg = _msg.trim();
	  return;
	}

	_query_id = items[2];
      }

      // Get the fqdn and fqdn_ace
      line = buffer.readLine();
      if (line == null) {
	return;
      }
      line = line.trim();
      String[] words = line.split("\\|");
      switch (words.length) {
      case 2:
	_fqdn_ace = words[1];
      case 1:
	_fqdn = words[0];
	break;
      default:
	return;
      }

      if (_status == 0 || _status == 5) {
	// Domain available or waiting release process
	return;
      }

      // Read a new line from the buffer
      line = buffer.readLine();
      if (line == null) {
	return;
      }
      line = line.trim();

      // Domain available with ticket: Get the list of active tickets
      if (_status == 1) {
	String[] tickets = line.split("\\|");
	for (int i = 0; i < tickets.length; i++) {
	  _tickets.add(new Integer(tickets[i]));
	}
	return;
      }

      // Domain already registered
      else if (_status == 2) {
	words = line.split("\\|");
	if (words.length < 2) {
	  return;
	}

	_expiration_date = words[0];
	_publication_status = words[1];
	for (int i = 2; i < words.length; i++) {
	  _nameservers.add(words[i]);
	}

	// Check if there's any suggestion
	line = buffer.readLine();
	if (line == null) {
	  return;
	}
	line = line.trim();

	String[] suggestions = line.split("\\|");
	for (int i = 0; i < suggestions.length; i++) {
	  _suggestions.add(suggestions[i] + ".br");
	}

	return;
      }

      // Domain unavailable or invalid
      else if (_status == 3 || _status == 4) {
	// Just get the message
	_msg = line;

	if (_status == 3) {
	  // Check if there's any suggestion
	  line = buffer.readLine();
	  if (line == null) {
	    return;
	  }
	  line = line.trim();
	 
	  String[] suggestions = line.split("\\|");
	  for (int i = 0; i < suggestions.length; i++) {
	    _suggestions.add(suggestions[i] + ".br");
	  }
	}

	return;
      }

      // Release process
      else if (_status == 6 || _status == 7) {
	// Get the release process dates
	_release_process_dates = line.split("\\|");
	if (_release_process_dates.length < 2) {
	  return;
	}

	// Get the tickets (status 7)
	if (_status == 7) {
	  line = buffer.readLine();
	  if (line == null) {
	    return;
	  }
	  line = line.trim();

	  String[] tickets = line.split("\\|");
	  for (int i = 0; i < tickets.length; i++) {
	    _tickets.add(new Integer(tickets[i]));
	  }
	}
      }
      return;
    }
  }
}
