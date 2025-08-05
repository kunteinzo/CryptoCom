from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes
from cryptography.hazmat.primitives.asymmetric import rsa, padding
from cryptography.hazmat.primitives import serialization, hashes
from cryptography.hazmat.backends import default_backend

import os, base64

def test_aes():
    key = b"12345678901234567890123456789012" # 32 bytes for 256 bits
    iv = b"123456789012"
    ci = Cipher(algorithms.AES(key), modes.GCM(iv), default_backend())
    en = ci.encryptor()
    enc = iv + en.update(b"Hello") + en.finalize() + en.tag
    print( 'Encrypted:',base64.b64encode(enc).decode())

    droot = enc
    div = droot[:12]
    dat = droot[12:-16]
    tag = droot[-16:]

    ci2 = Cipher(algorithms.AES(key), modes.GCM(iv, tag), default_backend())
    decryptor = ci2.decryptor()

    print('Decrypted:',(decryptor.update(dat)+decryptor.finalize()).decode())


def test_rsa():
    text = b'Hello'
    private_key = rsa.generate_private_key(65537, 2048, default_backend())
    public_key = private_key.public_key()

    encrypted = public_key.encrypt(
        text,
        padding.OAEP(
            padding.MGF1(hashes.SHA512()),
            hashes.SHA512(),
            None
        )
    )

    print("Encrypted:", base64.b64encode(encrypted).decode())

    decrypted = private_key.decrypt(
        encrypted,
        padding.OAEP(
            padding.MGF1(hashes.SHA512()),
            hashes.SHA512(),
            None
        )
    )

    print("Decrypted:", decrypted.decode())



if __name__ == '__main__':
    test_aes()
    test_rsa()