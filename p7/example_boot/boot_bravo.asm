[bits 16]
[org 0x7c00]

mov sp, 0x9000
mov bp, sp

mov al, 'H'
call print_char
mov al, 'i'
call print_char
jmp $

print_char:
push ax
mov ah,0x0e
int 0x10
pop ax
ret

times 510-($-$$) db 0
dw 0xaa55