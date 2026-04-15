[bits 16]
[org 0x7c00]

mov sp, 0x9000
mov bp, sp

call setup_timer
sti
jmp $               ; halt, interrupts do the work

; Install IRQ0 handler and reprogram PIT to ~100 Hz
setup_timer:
    cli
    ; IRQ0 = INT 8, vector at address 32 (8 * 4)
    mov [32], word timer_handler
    mov [34], cs
    ; PIT channel 0: lobyte/hibyte, mode 2 (rate gen), binary -> 0x34
    ; divisor 11932: 1193182 Hz / 11932 = ~100 Hz
    mov al, 0x34
    out 0x43, al
    mov al, 0x9C        ; lo byte of 11932 (0x2E9C)
    out 0x40, al
    mov al, 0x2E        ; hi byte of 11932
    out 0x40, al
    sti
    ret

timer_handler:
    pusha
    inc word [tick_count]
    cmp word [tick_count], 100  ; 100 ticks * 10ms = 1 second
    jl .done
    mov word [tick_count], 0
    mov si, msg
    call print_string
.done:
    mov al, 0x20        ; non-specific EOI to PIC
    out 0x20, al
    popa
    iret

print_string:
    mov ax, 0x0e00      ; teletype output
.loop:
    mov al, [si]
    test al, al
    jz .done
    int 0x10
    inc si
    jmp .loop
.done:
    ret

tick_count  dw 0
msg         db 'Tick...', 13, 10, 0

times 510-($-$$) db 0
dw 0xaa55
