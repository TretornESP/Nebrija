#include <krnl/nebrija.h>

#include <krnl/mem/pmm.h>
#include <krnl/mem/vmm.h>
#include <krnl/libraries/std/string.h>
#include <krnl/debug/debug.h>

void test_vmm() {
    vmm_root_t * root = vmm_get_root();
    void * physical_address = pmm_alloc_pages(1);
    char * virtual_address_rw = (char * )0xcafebabe000;
    char * virtual_address_r =  (char * )0xdeadbeef000;

    status_t st = vmm_map_pages(
        root,
        (uint64_t)virtual_address_rw,
        (uint64_t)physical_address,
        0x1, //Present
        0x1000, //Size
        0x1
    );

    if ( st != 0) {
        kprintf("Failed to map pages\n");
        return;
    }

    st = vmm_map_pages(
        root,
        (uint64_t)virtual_address_r,
        (uint64_t)physical_address,
        0x1, //Present
        0x1000, //Size
        0
    );

    if (st != 0) {
        kprintf("Failed to map pages\n");
        return;
    }

    strcpy(virtual_address_rw, "Hello, Nebrija's VMM!");
    kprintf("Written to virtual address: %s\n", virtual_address_rw);
    kprintf("Read from the other virtual address: %s\n", virtual_address_r);
    kprintf("Attempting to write to the other virtual address...\n");
    strcpy(virtual_address_r, "This should fail!"); //Esto debería causar un error
}

void run_nebrija_tests() {
    kprintf("Running Nebrija's tests...\n");
    test_vmm();
    while (1) {
        //Bucle infinito para mantener el kernel corriendo después de los tests
    }
}