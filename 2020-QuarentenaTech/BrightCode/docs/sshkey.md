## Criando SSH Key e Configurando Git

### Criando chave SSH

```sh
$ ssh-keygen -t rsa -C "email_git"
Generating public/private rsa key pair.
Enter file in which to save the key (<dir>/.ssh/id_rsa):  [ENTER]
Enter passphrase (empty for no passphrase):  [ENTER]
Enter same passphrase again:  [ENTER]
Your identification has been saved in <dir>/.ssh/id_rsa.
Your public key has been saved in <dir>/.ssh/id_rsa.pub.
```

Agora vamos copiar a chave gerada e colocar nas configurações do gitLab

```sh
$ cat <dir>\.ssh\id_rsa.pub
```

Após isso copie o resultado e cole no gitlab.

### Configurando Git
```sh
$ git config --global user.name "YOUR_USERNAME"

$ git config --global user.email "your_email_address@example.com"
```

