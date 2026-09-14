# Database CA certificate

`ca.pem` is the public certificate authority used to verify the managed MySQL server. It contains no private key and is safe to commit.

Never add client private keys, service passwords, or private connection strings to this directory.
