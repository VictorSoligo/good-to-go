# GoodToGo

Acadêmicos: Eduardo Farah, João Thomazoni e Victor Hugo Soligo.

# Executar o Ambiente de Desenvolvimento

## Requisitos

Certifique-se de que você tenha os seguintes softwares instalados:
  - Docker (https://www.docker.com/)
  - Docker Compose
  - Visual Studio Code

## Passos para Intalação

### Clone o repositório
``` bash
git clone https://github.com/VictorSoligo/good-to-go
cd good-to-go
```

## Como Rodar o Projeto

### Inicie o Docker
- Certifique-se de que o Docker está em execução na sua máquina.
- Certifique-se que nenhum container esteja rodando pelo comando "docker ps". 
- Se um ou mais containers estiverem rodando, execute o comando "docker stop $(docker ps -a -q)" para parar todas as execuções.

### Visual Studio Code
- Abra a pasta root do projeto no editor.
- Certifique-se de que a extensão "Dev Containers" fornecida pela Microsoft esteja instalada no editor.
- Abra a paleta de comandos acessada pelo atalho "Ctrl shift P".
- Execute o comando "Dev Containers: Rebuild Without Cache and Reopen in Container".
- Aguarde as instalações serem concluídas.
- Em um novo termial execute o comando "chmod +x dev.sh" para dar permissão de execução para o script "dev.sh".
- Execute o script "dev.sh".
