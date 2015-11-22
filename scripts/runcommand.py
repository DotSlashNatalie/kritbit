#!/bin/python

import shlex
from subprocess import Popen, PIPE
import sys
import rijndael
import base64
import string
import random
import json
import time
import hashlib
import pycurl
from urllib import urlencode

try:
    from cStringIO import StringIO
except ImportError:
    try:
        from StringIO import StringIO
    except ImportError:
        from io import StringIO

#
#  REPLACE THESE VALUES
#

SHARED_KEY = ""
HASH = ""
URL = ""

#
#
#

# source http://stackoverflow.com/a/8232171/195722
KEY_SIZE = 16
BLOCK_SIZE = 32
# JamPAo52/smpiObKa8p/MY5WAeDww0cOg9KiG6gMAYQ=

def curl_post(url, postvals, header = []):
    buffer = StringIO()
    cobj = pycurl.Curl()
    cobj.setopt(pycurl.URL, url)
    cobj.setopt(pycurl.POST, 1)
    cobj.setopt(pycurl.WRITEDATA, buffer)
    postdata = urlencode(postvals)
    cobj.setopt(pycurl.POSTFIELDS, postdata)
    cobj.setopt(pycurl.HTTPHEADER, header)
    cobj.perform()
    cobj.close()
    return buffer

def get_exitcode_stdout_stderr(cmd):
    """
    Execute the external command and get its exitcode, stdout and stderr.
    """
    args = shlex.split(cmd)

    proc = Popen(args, stdout=PIPE, stderr=PIPE, shell=True)
    out, err = proc.communicate()
    exitcode = proc.returncode
    #
    return exitcode, out, err

def encrypt(key, plaintext):
    padded_key = key.ljust(KEY_SIZE, '\0')
    padded_text = plaintext + (BLOCK_SIZE - len(plaintext) % BLOCK_SIZE) * '\0'

    # could also be one of
    #if len(plaintext) % BLOCK_SIZE != 0:
    #    padded_text = plaintext.ljust((len(plaintext) / BLOCK_SIZE) + 1 * BLOCKSIZE), '\0')
    # -OR-
    #padded_text = plaintext.ljust((len(plaintext) + (BLOCK_SIZE - len(plaintext) % BLOCK_SIZE)), '\0')

    r = rijndael.rijndael(padded_key, BLOCK_SIZE)

    ciphertext = ''
    for start in range(0, len(padded_text), BLOCK_SIZE):
        ciphertext += r.encrypt(padded_text[start:start+BLOCK_SIZE])

    encoded = base64.b64encode(ciphertext)

    return encoded


def decrypt(key, encoded):
    padded_key = key.ljust(KEY_SIZE, '\0')

    ciphertext = base64.b64decode(encoded)

    r = rijndael.rijndael(padded_key, BLOCK_SIZE)

    padded_text = ''
    for start in range(0, len(ciphertext), BLOCK_SIZE):
        padded_text += r.decrypt(ciphertext[start:start+BLOCK_SIZE])

    plaintext = padded_text.split('\x00', 1)[0]

    return plaintext
    

start_time = time.time()
exitcode, out, err = get_exitcode_stdout_stderr(sys.argv[1])
total = time.time() - start_time
nonce = ''.join(random.SystemRandom().choice(string.hexdigits + string.digits) for _ in range(10))
message = {}
message["nonce"] = nonce
message["message"] = json.dumps({"output":out, "time_taken": total, "result": 1})
message["signature"] = hashlib.sha256(message["message"] + nonce + HASH).hexdigest()
message["message"] = encrypt(SHARED_KEY, message["message"])
print curl_post(URL, {"data": json.dumps(message)}).getvalue()