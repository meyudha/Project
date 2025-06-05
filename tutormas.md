# MOHON DIBACA YGY
## Instal git CLI
copy/paste untuk instal paket gh
```
(type -p wget >/dev/null || (sudo apt update && sudo apt-get install wget -y)) \
	&& sudo mkdir -p -m 755 /etc/apt/keyrings \
        && out=$(mktemp) && wget -nv -O$out https://cli.github.com/packages/githubcli-archive-keyring.gpg \
        && cat $out | sudo tee /etc/apt/keyrings/githubcli-archive-keyring.gpg > /dev/null \
	&& sudo chmod go+r /etc/apt/keyrings/githubcli-archive-keyring.gpg \
	&& echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/githubcli-archive-keyring.gpg] https://cli.github.com/packages stable main" | sudo tee /etc/apt/sources.list.d/github-cli.list > /dev/null \
	&& sudo apt update \
	&& sudo apt install gh -y
```
copy/paste lagi
```
sudo apt update
sudo apt install gh
```
### LOGIN 
copy paste buat login
```
gh auth login
```
Nanti ada pertanyaan  
<b>? Where do you use GitHub?</b>
```
->Github.com  
```
<b>? What is your preferred protocol for Git operations on this host?</b>
```
->HTTPS  
```
<b>? Authenticate Git with your GitHub credentials?</b>
```
->Yes  
```
<b>? How would you like to authenticate GitHub CLI?</b>
```
->Login with a web browser
```
Ntar dikasih kode, masukin di browser
### CLONE
clone repo
```
gh repo clone meyudha/Project
```

## Upload files
**Kalo udah edit file, cara push nya gini**  
Tambahin ke repo lokal dulu
```
git add .
```
Commit ke repo local
```
git commmit -m "<bebas isi apa aja>"
```
Push ke repo remote
```
git push
```
