### Iniciando com GitFlow


Abra o projeto e inicie o git flow (necessário apenas a primeira vez)


```sh
$ git flow init
Which branch should be used for bringing forth production releases?
   - master
Branch name for production releases: [master] production

Which branch should be used for integration of the "next release"?
   - master
Branch name for "next release" development: [develop] master
```

Agora as opções abaixo podem ser apenas dado enter

```
How to name your supporting branch prefixes?
Feature branches? [feature/]
Bugfix branches? [bugfix/]
Release branches? [release/]
Hotfix branches? [hotfix/]
Support branches? [support/]
Version tag prefix? []
Hooks and filters directory? [<dir>/.git/hooks]
```


### Começando uma feature ou hotfix

#### Primeiro precisamos atualizar todas as nossas dependencias de arquivos.

`git fetch`

`git checkout production`

`git pull origin`

<br>

`git checkout master`

`git pull origin`

<br>

`git checkout develop`

`git pull origin`

<br>

#### Agora com tudo atualizado podemos criar uma Feature ou Hotfix

`git flow feature start task-numeroChamado`

`git flow hotfix start task-numeroChamado`


<br><br>

#### Após finalizada todas as alterações, devemos commitar e publicar nossas alterações.

Primeiro vamos adicionar tudo, usando o comando 
`git add .`

Agora vamos adicionar uma menssagem ao nosso commit seguindo o seguinte padrao.

`git commit -m "#NumerodoChamado - Alterei o readme dos arquivos"`

Agora vamos publicar nossa branch usando o seguinte comando.

`git flow feature publish`
