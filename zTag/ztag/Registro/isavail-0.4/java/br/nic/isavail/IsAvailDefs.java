// $Id: IsAvailDefs.java 49 2007-02-22 17:53:53Z eduardo $
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

/** Defaults for br.nic.isavail package */
public class IsAvailDefs {
  // File where the cookie is stored
  public static final String COOKIE_FILE = "/tmp/isavail-cookie.txt";

  // Default Server Address and port
  public static final String SERVER_ADDR = "whois.registro.br";
  public static final int SERVER_PORT = 43;

  public static final int MAX_UDP_SIZE = 512;
  public static final String DEFAULT_COOKIE = "00000000000000000000";

  // Maximum retries and interval
  public static final int MAX_RETRIES = 3;
  public static final int RETRY_TIMEOUT = 5;

  public static final int MAX_INT = 2147483647;
}
