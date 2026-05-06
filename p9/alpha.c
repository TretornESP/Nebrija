#include <stdio.h>
#include <stdlib.h>
#include <string.h>

//Codigo opcional, solo usado en exploit.py, no en exp2
void runshell() {
    system("/bin/bash");
}

void vulnerable() {
    char buffer[64];
    gets(buffer);
    write(1, buffer, strlen(buffer));
}

int main(int argc, char *argv[]) {
    vulnerable();
    return 0;
}