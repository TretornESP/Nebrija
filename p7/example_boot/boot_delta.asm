[bits 16]
[org 0x7c00]

mov sp, 0x9000
mov bp, sp

mov si, msg
call install_keyboard_interrupt
call print_string
jmp $

;Use int 0x10 to print the chars
print_string:
    mov ax, 0x0e00 ; teletype output
.print_loop:
    mov al, [si]
    test al, al
    jz .done
    int 0x10
    inc si
    jmp .print_loop
.done:
    ret

install_keyboard_interrupt:
    cli
    mov [36], word keyboard_interrupt_handler
    mov [38], cs
    sti
    ret

keyboard_interrupt_handler:
    pusha
    in al, 0x60
    test al, 0x80
    jnz .no_key

    mov bl, al
    xor bh, bh
    mov al, [cs:bx + keymap]

    mov ah, 0x0e
    int 0x10

.no_key:
    mov al, 0x61
    out 0x20, al
    popa
    iret

msg db 'Hello, World!', 0

keymap:
%include "keymap.inc"

times 510-($-$$) db 0
dw 0xaa55