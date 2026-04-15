[bits 16]
[org 0x7c00]

mov sp, 0x9000
mov bp, sp

mov si, msg
call print_string
call read_keypress

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

read_keypress:
.wait:
    in al, 0x64        ; Read keyboard status register
    test al, 0x01      ; Check Output Buffer Full flag (bit 0)
    jz .wait           ; No new data yet, keep polling
    in al, 0x60        ; Read scan code only when data is ready
    test al, 0x80      ; Bit 7 set = key release event, ignore it
    jnz read_keypress
    mov bl, al
    xor bh, bh
    mov al, [cs:bx + keymap]
    mov ah, 0x0e
    int 0x10
    jmp read_keypress

msg db 'Hello, World!', 0

keymap:
%include "keymap.inc"

times 510-($-$$) db 0
dw 0xaa55