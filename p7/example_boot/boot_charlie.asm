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
    mov si, 0          ; Reset buffer index
.wait:
    in al, 0x64        ; Read keyboard status register
    test al, 0x01      ; Check Output Buffer Full flag (bit 0)
    jz .wait           ; No new data yet, keep polling
    in al, 0x60        ; Read scan code only when data is ready
    test al, 0x80      ; Bit 7 set = key release event, ignore it
    jnz .wait
    mov bl, al
    xor bh, bh
    mov al, [cs:bx + keymap]
    mov ah, 0x0e
    int 0x10
    ;test if enter key was pressed
    ;if so go to execute_commands
    ;else store the char in a buffer and keep reading keypresses
    cmp al, 0x0d      ; Check if Enter key (carriage return) was pressed
    je execute_commands
    ; Store the character in a buffer
    mov [command_buffer + si], al
    inc si
    jmp read_keypress

execute_commands:
    ;Print new line
    mov ah, 0x0e
    mov al, 0x0d      ; Carriage return
    int 0x10
    mov al, 0x0a      ; Line feed
    int 0x10
    ; Null-terminate the command buffer
    mov byte [command_buffer + si], 0
    ; Check if the command is "ls"
    mov si, command_buffer
    mov ax, 0
    mov cx, 2
.compare_loop:
    mov al, [si]
    test al, al
    jz .not_ls
    cmp al, 'l'
    je .check_second_char
    jmp .not_ls
.check_second_char:
    inc si
    mov al, [si]
    cmp al, 's'
    je .execute_ls
    jmp .not_ls
.not_ls:
    ; If command is not recognized, print an error message
    mov si, error_msg
    call print_string
    jmp read_keypress
.execute_ls:
    ; For demonstration, just print a message instead of listing files
    mov si, fake_ls_output
    call print_string
    jmp read_keypress

msg db 'r@h:', 0
error_msg db 'err', 0
fake_ls_output db 'a.txt', 0
command_buffer db 64 dup(0)
keymap:
%include "keymap.inc"

times 510-($-$$) db 0
dw 0xaa55