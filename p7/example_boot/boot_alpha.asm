[bits 16]
[org 0x7c00]

mov bp, 0x9000
mov sp, bp

mov si, msg
call imprime_cadena
jmp $

imprime_cadena:
    mov bx, 0
.loop:
    mov al, [si + bx]
    cmp al, 0
    je .fin_cadena
    call imprime_caracter
    inc bx
    jmp .loop
.fin_cadena:
    ret

imprime_caracter:
    mov ah, 0x0e
    int 0x10
    ret

msg db 'hola que tal estas? en un lugar de la mancha', 0
times 510-($-$$) db 0
dw 0xaa55