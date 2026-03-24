````md
# 🖥️ Guia Completo: Como Fazer Dock DisplayLink (Wavlink WL-UG39DK7) Funcionar no Ubuntu 24.04+

> Guia detalhado, pensado para usuários iniciantes, para corrigir problemas de docks com DisplayLink no Ubuntu 24.04+  
> Testado em ambiente com Ubuntu 24.04.4 LTS e kernels recentes  
> Compatível com docks baseadas em DisplayLink, como a **Wavlink WL-UG39DK7** e modelos semelhantes

---

## 📚 Índice

- [Visão geral](#-visão-geral)
- [Problema](#-problema)
- [Causa](#-causa)
- [Estratégia da solução](#-estratégia-da-solução)
- [Pré-requisitos](#-pré-requisitos)
- [Passo 1 — Verificar a versão do kernel](#passo-1--verificar-a-versão-do-kernel)
- [Passo 2 — Desativar o Secure Boot](#passo-2--desativar-o-secure-boot)
- [Passo 3 — Instalar dependências](#passo-3--instalar-dependências)
- [Passo 4 — Baixar o driver oficial do DisplayLink](#passo-4--baixar-o-driver-oficial-do-displaylink)
- [Passo 5 — Extrair o conteúdo baixado](#passo-5--extrair-o-conteúdo-baixado)
- [Passo 6 — Dar permissão ao instalador](#passo-6--dar-permissão-ao-instalador)
- [Passo 7 — Extrair o `.run` sem instalar](#passo-7--extrair-o-run-sem-instalar)
- [Passo 8 — Substituir o `evdi` antigo](#passo-8--substituir-o-evdi-antigo)
- [Passo 9 — Corrigir o script do instalador](#passo-9--corrigir-o-script-do-instalador)
- [Passo 10 — Instalar o driver corretamente](#passo-10--instalar-o-driver-corretamente)
- [Passo 11 — Reiniciar o sistema](#passo-11--reiniciar-o-sistema)
- [Passo 12 — Verificar se funcionou](#passo-12--verificar-se-funcionou)
- [Problemas comuns e soluções](#-problemas-comuns-e-soluções)
- [Wayland vs Xorg](#-wayland-vs-xorg)
- [Atualizações de kernel](#-atualizações-de-kernel)
- [Conclusão](#-conclusão)
- [Contribuição](#-contribuição)
- [Licença](#-licença)

---

# 🔎 Visão geral

Se você chegou até aqui, provavelmente conectou sua dock DisplayLink ao Ubuntu e os monitores externos não funcionaram como esperado.

Esse problema é muito comum no Ubuntu 24.04+ porque o driver oficial do DisplayLink ainda depende de uma versão do `evdi` que pode não funcionar corretamente com kernels mais recentes.

Este guia mostra, passo a passo, como corrigir isso de forma manual.

---

# 📌 Problema

Ao conectar uma dock DisplayLink no Ubuntu 24.04+, você pode encontrar um ou mais destes sintomas:

- ❌ monitores externos não são reconhecidos
- ❌ monitores são detectados, mas não exibem imagem
- ❌ apenas um monitor funciona
- ❌ tela preta
- ❌ travamentos ou comportamento inconsistente
- ❌ dock funciona parcialmente

---

# 🧠 Causa

Os principais motivos são:

- o driver oficial do DisplayLink usa uma versão antiga do `evdi`
- kernels mais novos do Ubuntu 24.04+ podem quebrar a compatibilidade com esse `evdi`
- o **Secure Boot** pode impedir o carregamento do módulo compilado manualmente
- em alguns casos, a sessão gráfica com **Wayland** também causa incompatibilidades

---

# ✅ Estratégia da solução

A ideia deste guia é:

1. confirmar que seu sistema está em um kernel afetado
2. desativar o Secure Boot
3. baixar o driver oficial
4. extrair o instalador sem executá-lo diretamente
5. substituir manualmente o `evdi` antigo por uma versão mais atual
6. corrigir o script do instalador
7. instalar o driver da forma correta
8. reiniciar e validar

---

# ⚠️ Pré-requisitos

Antes de começar, você vai precisar de:

- acesso ao terminal
- privilégios de administrador (`sudo`)
- conexão com a internet
- pacotes de compilação instalados
- Secure Boot desativado
- paciência para seguir os passos sem pular etapas

---

## Passo 1 — Verificar a versão do kernel

Abra o terminal e execute:

```bash
uname -r
````

Se o resultado for algo como:

```bash
6.8.x
6.11.x
6.14.x
6.17.x
```

ou qualquer kernel relativamente recente, este guia provavelmente é necessário para o seu caso.

---

## Passo 2 — Desativar o Secure Boot

### Por que isso é obrigatório?

O `evdi` é compilado como módulo do kernel. Quando o **Secure Boot** está ativado, o Ubuntu pode bloquear o carregamento desse módulo, mesmo que a instalação pareça ter dado certo.

### Como desativar

1. Reinicie o computador

2. Entre na BIOS/UEFI
   Normalmente usando uma tecla como:

   * `F2`
   * `DEL`
   * `ESC`
   * `F10`
   * `F12`

3. Procure pela opção:

```text
Secure Boot
```

4. Altere para:

```text
Disabled
```

5. Salve as alterações e reinicie

### Como verificar no Ubuntu

Depois de voltar ao sistema, rode:

```bash
mokutil --sb-state
```

Saída esperada:

```bash
SecureBoot disabled
```

Se aparecer `enabled`, desative corretamente antes de continuar.

---

## Passo 3 — Instalar dependências

Instale os pacotes necessários para compilar o módulo e extrair os arquivos:

```bash
sudo apt update
sudo apt install -y dkms build-essential linux-headers-$(uname -r) git unzip curl mokutil
```

Esses pacotes são importantes porque o processo depende de:

* compilação de código-fonte
* headers do kernel atual
* manipulação de arquivos compactados
* download do novo `evdi`
* verificação do Secure Boot

---

## Passo 4 — Baixar o driver oficial do DisplayLink

Acesse a página oficial de download da Synaptics / DisplayLink:

```text
https://www.synaptics.com/products/displaylink-graphics/downloads/ubuntu
```

### Atenção: este é o passo que costuma gerar confusão

Na página, localize a seção semelhante a esta:

```text
Latest Official Driver
DisplayLink USB Graphics Software for Ubuntu
Ubuntu 20.04, Ubuntu 22.04, Ubuntu 23.04, Ubuntu 24.04
Release: 6.2
```

Clique em **Download**.

### Aceite da licença

Depois disso, você será redirecionado para uma página de aceite da licença, em uma URL semelhante a:

```text
https://www.synaptics.com/products/displaylink-usb-graphics-software-ubuntu-62?filetype=exe
```

Nessa página:

1. role até a parte da licença
2. marque a opção de aceite
3. confirme o aceite

Você verá algo como:

```text
Please read and accept the following Software License Agreement:
```

Após aceitar, o download do arquivo será iniciado automaticamente.

### Arquivo baixado

O nome normalmente será algo parecido com:

```text
DisplayLink USB Graphics Software for Ubuntu6.2-EXE.zip
```

---

## Passo 5 — Extrair o conteúdo baixado

Agora vá para a pasta de downloads e extraia o arquivo:

```bash
cd ~/Downloads
unzip "DisplayLink USB Graphics Software for Ubuntu6.2-EXE.zip"
```

Isso deve criar uma pasta semelhante a:

```text
DisplayLink USB Graphics Software for Ubuntu6.2-EXE
```

Entre nela:

```bash
cd ~/Downloads/"DisplayLink USB Graphics Software for Ubuntu6.2-EXE"
```

---

## Passo 6 — Dar permissão ao instalador

Dentro da pasta extraída, localize o arquivo `.run`.

Dependendo da versão, ele pode ter um nome como:

```text
displaylink-driver-6.2.0-30.run
```

ou algo muito próximo disso.

Dê permissão de execução:

```bash
chmod +x displaylink-driver-6.2.0-30.run
```

> Se o nome do arquivo for um pouco diferente, use `ls` para conferir o nome exato:
>
> ```bash
> ls
> ```

---

## Passo 7 — Extrair o `.run` sem instalar

Esse passo é essencial.

Você **não deve executar a instalação diretamente** nesse momento.

Em vez disso, extraia o conteúdo interno do `.run` com:

```bash
./displaylink-driver-6.2.0-30.run --noexec --keep
```

### O que esse comando faz?

* `--noexec` evita que a instalação seja executada imediatamente
* `--keep` mantém os arquivos extraídos na pasta

Isso criará um diretório com os arquivos internos do instalador.

Agora entre na pasta extraída. Exemplo:

```bash
cd displaylink-driver-6.2.0
```

> O nome da pasta pode variar um pouco conforme a versão. Use `ls` se necessário.

---

## Passo 8 — Substituir o `evdi` antigo

O instalador traz um `evdi` antigo, e é justamente isso que costuma quebrar no Ubuntu 24.04+.

### Remova o arquivo antigo

Dentro da pasta extraída, rode:

```bash
rm evdi.tar.gz
```

### Baixe uma versão mais atual do `evdi`

```bash
curl -L https://github.com/DisplayLink/evdi/archive/refs/heads/devel.tar.gz -o evdi.tar.gz
```

Com isso, o instalador passará a usar uma base mais recente do `evdi`.

---

## Passo 9 — Corrigir o script do instalador

Agora vamos ajustar o script para extrair corretamente o conteúdo do novo `evdi`.

Abra o arquivo:

```bash
nano displaylink-installer.sh
```

Procure por esta linha:

```bash
tar xf "$TARGZ" -C "$EVDI"
```

Substitua por:

```bash
tar xf "$TARGZ" -C "$EVDI" --strip-components=1
```

### Por que isso é necessário?

Porque o `tar.gz` baixado do GitHub normalmente vem com uma pasta raiz extra.
Sem `--strip-components=1`, os arquivos podem ser extraídos em um nível de diretório errado, e o instalador pode falhar.

### Como salvar no nano

1. pressione `CTRL + O`
2. pressione `ENTER`
3. pressione `CTRL + X`

---

## Passo 10 — Instalar o driver corretamente

Agora sim execute o instalador corrigido:

```bash
sudo ./displaylink-installer.sh
```

### Muito importante: atenção à pergunta sobre APT

Durante o processo, pode aparecer algo como:

```text
Do you want to install with apt? (Y/n)
```

Responda:

```text
n
```

### Por que responder `n`?

Porque instalar via APT, nesse cenário, pode fazer o sistema usar a versão problemática e desfazer a correção manual.

Ou seja:

* ✅ resposta correta: `n`
* ❌ não responda `Y`

---

## Passo 11 — Reiniciar o sistema

Quando a instalação terminar, reinicie:

```bash
sudo reboot
```

---

## Passo 12 — Verificar se funcionou

Depois que o sistema voltar, abra o terminal e teste:

```bash
lsmod | grep evdi
```

Se aparecer algo relacionado a `evdi`, isso é um ótimo sinal.

Você também pode testar:

```bash
xrandr
```

Se seus monitores externos aparecerem na listagem, a dock foi reconhecida com sucesso.

Outro teste útil:

```bash
systemctl status displaylink-driver
```

Se o serviço estiver ativo, melhor ainda.

---

# 🛠️ Problemas comuns e soluções

## 1. O módulo `evdi` não carrega

Verifique:

```bash
lsmod | grep evdi
```

Se não houver retorno:

* confira se o Secure Boot está realmente desativado
* confirme se os headers do kernel atual estão instalados
* revise se a instalação foi feita com o script corrigido

---

## 2. Secure Boot ainda está ativado

Confira com:

```bash
mokutil --sb-state
```

Se o resultado for:

```bash
SecureBoot enabled
```

o módulo pode estar sendo bloqueado.
Volte à BIOS/UEFI e desative.

---

## 3. Erro ao compilar o `evdi`

Garanta que os headers do kernel atual estão instalados:

```bash
sudo apt install linux-headers-$(uname -r)
```

Também vale atualizar os pacotes de compilação:

```bash
sudo apt install --reinstall build-essential dkms
```

---

## 4. Instalou usando APT sem querer

Se durante o instalador você respondeu `Y` para instalar com APT, pode ter instalado uma versão problemática.

### Limpeza recomendada

```bash
sudo apt remove --purge displaylink-driver evdi -y
sudo rm -rf /var/lib/dkms/evdi
sudo apt autoremove -y
```

Depois disso, refaça o guia desde a extração do `.run`.

---

## 5. O monitor ainda não funciona, mesmo com o driver instalado

Nesse caso, verifique:

* se a dock está energizada corretamente
* se o cabo USB é adequado
* se o monitor está na entrada correta
* se você está usando Wayland
* se o serviço está ativo

Veja o status do serviço:

```bash
systemctl status displaylink-driver
```

Veja logs:

```bash
journalctl -u displaylink-driver
```

---

## 6. O Ubuntu detecta a dock, mas a sessão gráfica continua instável

Em alguns casos, a sessão Wayland pode causar problemas com docks e drivers gráficos externos.

---

# 🖼️ Wayland vs Xorg

Se ainda houver falhas, teste iniciar a sessão com **Xorg**.

## Como trocar

Na tela de login do Ubuntu:

1. clique no seu usuário
2. antes de entrar, clique no ícone de engrenagem ⚙️
3. selecione:

```text
Ubuntu on Xorg
```

4. faça login normalmente

Isso costuma melhorar a compatibilidade com o DisplayLink.

---

# 🔄 Atualizações de kernel

Sempre que o Ubuntu atualizar o kernel, existe a possibilidade de o driver parar de funcionar novamente.

Se isso acontecer:

* verifique a nova versão com `uname -r`
* teste se o `evdi` ainda está carregando
* se necessário, repita o procedimento deste guia

---

# 💡 Dicas importantes

* não pule o passo do aceite da licença
* não execute o `.run` diretamente sem extrair antes
* não responda `Y` para instalar com APT
* não mantenha o Secure Boot ativado
* se algo der errado, o problema geralmente está em um desses pontos

---

# 🧠 Conclusão

No Ubuntu 24.04+, a instalação do DisplayLink pode exigir intervenção manual.

O caminho que costuma funcionar melhor é:

* usar o driver oficial apenas como base
* substituir o `evdi` antigo por uma versão mais nova
* corrigir o script do instalador
* evitar a instalação via APT
* desativar o Secure Boot
* testar Xorg se necessário

Seguindo exatamente estes passos, as chances de a dock voltar a funcionar corretamente aumentam bastante.

---

# 🤝 Contribuição

Se este guia te ajudou:

* dê uma ⭐ no repositório
* abra uma issue se encontrar alguma diferença em versões futuras
* envie um pull request se quiser melhorar o passo a passo
* compartilhe com outras pessoas que estejam sofrendo com DisplayLink no Linux

---

# 📄 Licença

MIT

```
