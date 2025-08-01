from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes
from cryptography.hazmat.backends import default_backend

import os, base64

key = b"12345678901234567890123456789012" # 32 bytes for 256 bits
iv = b"123456789012"

ci = Cipher(algorithms.AES(key), modes.GCM(iv), default_backend())
en = ci.encryptor()

enc = iv + en.update(b"Hello") + en.finalize() + en.tag

print(base64.b64encode(enc).decode())

droot = enc
div = droot[:12]
dat = droot[12:-16]
tag = droot[-16:]

ci2 = Cipher(algorithms.AES(key), modes.GCM(iv, tag), default_backend())
decryptor = ci2.decryptor()

print((decryptor.update(dat)+decryptor.finalize()).decode())


