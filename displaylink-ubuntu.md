````md

# 🖥️ DisplayLink no Ubuntu 24.04+ (Guia Completo para Iniciantes)

> 🚀 Guia passo a passo para fazer docks DisplayLink funcionarem no Linux
> 💡 Testado no Ubuntu 24.04.4 LTS (Kernel 6.8+)
> 🔌 Compatível com Wavlink, Dell, Plugable e similares

---

## 📚 Índice

* [📌 Problema](#-problema)
* [🧠 Causa](#-causa)
* [✅ Solução (Resumo Rápido)](#-solução-resumo-rápido)
* [⚠️ Pré-requisitos](#️-pré-requisitos)
* [📥 1. Download do Driver (IMPORTANTE)](#-1-download-do-driver-importante)
* [⚙️ 2. Corrigir o EVDI (ESSENCIAL)](#️-2-corrigir-o-evdi-essencial)
* [⚙️ 3. Instalar o Driver](#️-3-instalar-o-driver)
* [🔐 4. Secure Boot](#-4-secure-boot)
* [🔄 5. Reiniciar](#-5-reiniciar)
* [🧪 6. Testar se Funcionou](#-6-testar-se-funcionou)
* [🛠️ Troubleshooting](#️-troubleshooting)
* [💡 Dicas Importantes](#-dicas-importantes)

---

## 📌 Problema

Se você conectou sua dock DisplayLink no Ubuntu 24.04+, provavelmente viu isso:

* ❌ Monitor externo não aparece
* ❌ Tela preta
* ❌ Travamentos
* ❌ Só um monitor funciona

---

## 🧠 Causa

Isso acontece porque:

* O driver oficial usa um `evdi` antigo
* O Ubuntu 24 usa kernel novo (6.8+)
* O driver ainda não acompanha essa evolução
* O Secure Boot pode bloquear o driver

---

## ✅ Solução (Resumo Rápido)

Você vai fazer basicamente 3 coisas:

1. Baixar o driver oficial (com um passo escondido ⚠️)
2. Corrigir manualmente o `evdi`
3. Instalar o driver

---

# ⚠️ Pré-requisitos

## ✔️ 1. Verifique seu kernel

```bash
uname -r
```

Se for **6.8 ou superior**, siga este guia.

---

## ✔️ 2. Instale dependências

```bash
sudo apt update
sudo apt install -y dkms build-essential linux-headers-$(uname -r) git unzip
```

---

# 📥 1. Download do Driver (IMPORTANTE)

👉 Acesse:
[https://www.synaptics.com/products/displaylink-graphics/downloads/ubuntu](https://www.synaptics.com/products/displaylink-graphics/downloads/ubuntu)

---

## ⚠️ PASSO CRÍTICO (FACILMENTE IGNORADO)

Na página:

1. Procure por:

```
Latest Official Driver
DisplayLink USB Graphics Software for Ubuntu
```

2. Clique em **Download**

---

## 🔐 Aceite da licença (OBRIGATÓRIO)

Você será redirecionado para outra página.

👉 Exemplo:
[https://www.synaptics.com/products/displaylink-usb-graphics-software-ubuntu-62?filetype=exe](https://www.synaptics.com/products/displaylink-usb-graphics-software-ubuntu-62?filetype=exe)

Agora:

* Role até o final
* Marque a caixa de aceite
* Clique em **Accept**

---

## ⬇️ Download automático

O arquivo será baixado:

```
DisplayLink USB Graphics Software for Ubuntu6.2-EXE.zip
```

---

## 📂 Extraia o arquivo

```bash
cd ~/Downloads
unzip "DisplayLink USB Graphics Software for Ubuntu6.2-EXE.zip"
```

---

## 📁 Entre na pasta

```bash
cd ~/Downloads/"DisplayLink USB Graphics Software for Ubuntu6.2-EXE"
```

---

# ⚙️ 2. Corrigir o EVDI (ESSENCIAL)

Esse é o passo mais importante do guia.

---

## 🔥 Remover versão antiga

```bash
sudo dkms remove evdi/1.12.0 --all || true
```

---

## 📥 Baixar versão nova

```bash
cd ~
git clone https://github.com/DisplayLink/evdi.git
cd evdi
```

---

## 📌 Usar versão compatível

```bash
git checkout v1.14.1
```

---

## ⚙️ Compilar

```bash
make
sudo make install
```

---

## 📦 Registrar no sistema

```bash
sudo dkms add .
sudo dkms build evdi/1.14.1
sudo dkms install evdi/1.14.1
```

---

# ⚙️ 3. Instalar o Driver

```bash
cd ~/Downloads/"DisplayLink USB Graphics Software for Ubuntu6.2-EXE"
```

```bash
sudo ./displaylink-driver-6.2.run
```

---

# 🔐 4. Secure Boot

Se não funcionar, provavelmente é isso.

---

## ✔️ Opção fácil

Desative o Secure Boot na BIOS

---

## ✔️ Opção avançada

Assinar módulo manualmente (não recomendado para iniciantes)

---

# 🔄 5. Reiniciar

```bash
sudo reboot
```

---

# 🧪 6. Testar se Funcionou

```bash
xrandr
```

Se aparecer mais telas → 🎉 sucesso!

---

# 🛠️ Troubleshooting

## 🔍 Ver serviço

```bash
systemctl status displaylink-driver
```

---

## 🔍 Ver logs

```bash
journalctl -u displaylink-driver
```

---

## 🔍 Ver módulo

```bash
lsmod | grep evdi
```

---

## ❌ Ainda não funcionou?

Verifique:

* Secure Boot ativo
* Kernel muito novo
* Cabo USB ruim
* Dock sem energia

---

# 💡 Dicas Importantes

* Sempre use versão nova do `evdi`
* O driver oficial quase sempre está desatualizado
* Reiniciar resolve vários problemas
* Use cabo USB 3.0 ou superior

---

# ⭐ Contribuição

Se esse guia te ajudou:

* ⭐ Dê uma estrela no repositório
* 🛠️ Sugira melhorias
* 🧠 Compartilhe com outros

---

# 🧾 Licença

Este guia é livre para uso e modificação.

---

## 🚀 Resultado Final Esperado

* ✅ Dock funcionando
* ✅ Monitores detectados
* ✅ Sistema estável

---
