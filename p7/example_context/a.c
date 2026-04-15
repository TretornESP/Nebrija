#include <unistd.h>

int a11, a12, a13;
int ready = 0;

void b();

void kyield() {
    int a[10] = {5, 5, 5, 5, 5, 5, 5, 5, 5, 5};

    if (!ready) {
        a11 = a[11];
        a12 = a[12];
        a13 = a[13];
        ready = 1;
        b();
    } else {
        int tmp11 = a11;
        int tmp12 = a12;
        int tmp13 = a13;
        a11 = a[11];
        a12 = a[12];
        a13 = a[13];
        a[11] = tmp11;
        a[12] = tmp12;
        a[13] = tmp13;
        return;
    }
}

void kwrite(const char *str) {
    kyield();
    write(1, str, 1);
}

void a() {
    int times = 10;
    while (times--)
        kwrite("A");
}

void b() {
    int times = 10;
    while (times--)
        kwrite("B");
}

int main() {
    a();
}