from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes
from cryptography.hazmat.backends import default_backend

import os, base64

key = b"12345678901234567890123456789012" # 32 bytes for 256 bits
iv = b"123456789012"

ci = Cipher(algorithms.AES(key), modes.GCM(iv), default_backend())
en = ci.encryptor()

print(base64.b64encode(iv + en.update(b"Hello") + en.finalize() + en.tag).decode())