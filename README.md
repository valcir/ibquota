# IBQUOTA 3 - CUPS BACKEND

 Print Accounting Software

AJUDE NO DESENVOLVIMENTO: Reporte erros ou sugestões!

    Versão 3.2: 20/fev/19
    Versão 3.3: (Aguardando retorno dos usuarios) 

Instalação

1 - Executar os processos de instalação dos pacotes.

    $ sudo apt-get install apache2 build-essential cups pkpgcounter mysql-server php libdbd-mysql-perl libnet-ldap-perl php-ldap

1.1 - """(APENAS se o pacote pkpgcounter não exister em sua distribuição, ex CentOS)""" Instalação manual do contador de páginas pkpgcounter:

    $ cd /tmp
    $ wget http://www.pykota.com/software/pkpgcounter/download/tarballs/pkpgcounter-3.50.tar.gz
    $ tar -zxf pkpgcounter-3.50.tar.gz
    $ cd pkpgcounter-3.50
    $ python setup.py install


2 - Download IBQUOTA 3 (versão em Desenvolvimento)

    $ wget https://github.com/valcir/ibquota/archive/master.zip
    ou
    $ git clone https://github.com/valcir/ibquota.git

3 - Configurando o cups.

    $ sudo vi /etc/cups/cupsd.conf

Localizar a linha "Listen localhost:631" e alterar para:

    Listen 631

Dar permissão de acesso ao CUPS, altere as linhas conforme abaixo:

    #Restrict access to the server...
    <Location />
      Allow all
      Order allow,deny
    </Location>

    #Restrict access to the admin pages...
    <Location /admin>
      Allow all
      Order allow,deny
    </Location>

    #Restrict access to configuration files...
    <Location /admin/conf>
      Allow all
      AuthType Default
      Require user @SYSTEM
      Order allow,deny
    </Location>

4 - Banco de Dados:
 
Criar o banco de dados:

    $ sudo mysql -u root -p
    $ password: *****
    $ mysql> CREATE DATABASE ibquota3;
    $ mysql> exit

Criar a estrutura do Banco (tabelas) através do script ibquota3.sql.
    
    $ cd ibquota3/sql
    $ sudo mysql -u root -p ***** ibquota3 < ibquota3.sql

Criar um usuário no Banco:

    $ sudo mysql -u root -p
    mysql> GRANT ALL ON ibquota3.* TO ibquota@localhost identified by 'ibquota';
    mysql> FLUSH PRIVILEGES;
    mysql> exit
 
O script principal (ibquota3) deverá ser copiado para dentro do CUPS.

    $ cd backend
    $ sudo cp ibquota3 /usr/lib/cups/backend/
    $ cd /usr/lib/cups/backend
    $ sudo chmod 755 ibquota3
    $ sudo chown root ibquota3

Agora temos que editar o backend:

    $ sudo vi /usr/lib/cups/ibquota3

    my $DBhost="localhost";
    my $DBlogin="ibquota";
    my $DBpassword="ibquota";
    my $DBdatabase="ibquota3";
    my $DBport=3306;
 
    $ cd ../gg
    $ sudo mkdir /var/www/html/gg
    $ sudo cp -r * /var/www/html/gg

Neste momento iremos editar o arquivo com as configurações de acesso a banco.

    $ cd /var/www/html/gg
    $ sudo vi includes/db.php

    define("HOST", "localhost");     // Servidor com o qual voce quer se conectar.
    define("USER", "ibquota");       // Usuário para acessar o banco de dados. 
    define("PASSWORD", "ibquota");   // Senha de acesso ao banco de dados. 
    define("DATABASE", "ibquota3");  // O nome do banco de dados.

5 - Reiniciar o CUPS:

    $ sudo /etc/init.d/cups restart

6 - Instalando a impressora no servidor.

Acesse o CUPS:

http://ip_do_servidor:631

Em "Administration", selecione:

    "Show printers shared by other systems"
    "Share printers connected to this system"
    "Allow printing from the Internet"
    "Allow remote administration"
    "Allow users to cancel any job (not just their own)" 

Em "Administration", clique em "Add Printer" Faça a instalação da impressora prestando atenção em "connection".

    connection: ibquota3:socket://IP-DA-IMPRESSORA

7 - Configuração via GG:

    - Acesse o GG http://ip_do_servidor/gg 
    - Login com "admin" (A senha será resetada)
    - Configuração principal (Paths, Base de usuários [SQL ou LDAP/AD], DEBUG)
    - Criar Usuário e Grupo, se Base for SQL;
    - Criar Política de Impressão
    - Faça um teste de impressão... 
    
8 - Teste a configuração do Backend:

    $ perl /usr/lib/cups/backend/ibquota3 --check
    
    PATH_PYTHON = /usr/bin/python [OK]
    Python is executable [OK]
    PATH_PKPGCOUNTER = /usr/bin/pkpgcounter [OK]
    Base de Dados: LOCAL SQL [OK]
    ...

Obrigado pelo interesse no IBQUOTA!

