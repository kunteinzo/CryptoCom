from cryptography.hazmat.backends import default_backend
from cryptography.hazmat.primitives.asymmetric import rsa, padding
from cryptography.hazmat.primitives import hashes, serialization
from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes

import os, base64

key = os.urandom(32)
iv = os.urandom(12)
cipher = Cipher(algorithms.AES(key), modes.GCM(iv), backend=default_backend())

encryptor = cipher.encryptor()

encrypted = encryptor.update(b'Hello world!') + encryptor.finalize() + encryptor.tag

decryptor = cipher.decryptor()

decrypted = decryptor.update(encrypted[:-16]) + decryptor.finalize_with_tag(encrypted[-16:])

print(decrypted.decode())