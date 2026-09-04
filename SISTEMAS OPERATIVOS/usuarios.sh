#!/bin/bash

# CONFIGURACIÓN
opcion=10
fecha=$(date +%Y-%m-%d)


# ==========================================================
# MENÚ PRINCIPAL
# ==========================================================
menu_principal() {
    clear

    echo "=========================================="
    echo "       GESTIÓN DE USUARIOS - SISTEMA      "
    echo "=========================================="
    echo " 1) Crear usuario"
    echo " 2) Eliminar usuario"
    echo " 3) Mostrar usuarios"
    echo " 4) Consultar usuario"
    echo " 5) Modificar contraseña"
    echo " 6) Bloquear cuenta"
    echo " 7) Desbloquear cuenta"
    echo " 0) Salir"
    echo "=========================================="
}


# ==========================================================
# AGREGAR USUARIO
# ==========================================================
agregar() {
    clear

    echo "Ingrese apellido y nombre sin espacios:"
    read nombre

    usuario=$(echo "$nombre" | tr '[:upper:]' '[:lower:]')
    cantidad=$(grep -c "^$usuario:" /etc/passwd)

    if [ "$cantidad" -eq 1 ]; then

        echo "El usuario $usuario ya se encuentra registrado."
        echo "[$(date +%Y-%m-%d-%H:%M:%S)] $USER intentó crear el usuario $usuario, pero ya existe." \
        >> /root/log/log_propios/usuarios.txt

        read -p "Presione ENTER para continuar..."

    else

        echo "Ingrese el grupo al que pertenecerá:"
        read grupo

        grupo_usuario=$(echo "$grupo" | tr '[:upper:]' '[:lower:]')
        grupo_existe=$(grep -c "^$grupo_usuario:" /etc/group)

        if [ "$grupo_existe" -eq 1 ]; then

            useradd -g "$grupo_usuario" \
            -c "$grupo_usuario $fecha" \
            -mk /etc/skel \
            -s /bin/bash "$usuario"

            echo "$usuario:12345" | chpasswd

            echo "[$(date +%Y-%m-%d-%H:%M:%S)] $USER creó al usuario $usuario del grupo $grupo_usuario." \
            >> /root/log/log_propios/usuarios.txt

            echo "Usuario creado correctamente."
            echo "Contraseña inicial: 12345"

            read -p "Presione ENTER para continuar..."

        else

            echo "El grupo indicado no existe."

            echo "[$(date +%Y-%m-%d-%H:%M:%S)] Se intentó agregar al grupo $grupo_usuario, pero no existe." \
            >> /root/log/log_propios/grupos.txt

            read -p "Presione ENTER para continuar..."
        fi
    fi
}


# ==========================================================
# ELIMINAR USUARIO
# ==========================================================
eliminar() {
    clear

    echo "Ingrese apellido y nombre sin espacios:"
    read nombre

    usuario=$(echo "$nombre" | tr '[:upper:]' '[:lower:]')
    cantidad=$(grep -c "^$usuario:" /etc/passwd)

    if [ "$cantidad" -eq 1 ]; then

        echo "¿Desea eliminar al usuario $usuario? [S/N]"
        read respuesta

        if [ "$respuesta" = "S" ] || [ "$respuesta" = "s" ]; then

            echo "Usuario eliminado del sistema."
            echo "[$(date +%Y-%m-%d-%H:%M:%S)] Usuario $usuario eliminado." \
            >> /root/log/log_propios/usuarios.txt

            read -p "Presione ENTER para continuar..."

        else

            echo "Operación cancelada."
            read -p "Presione ENTER para volver al menú..."

        fi

    else

        echo "No se encontró el usuario indicado."
        read -p "Presione ENTER para volver al menú..."
    fi
}


# ==========================================================
# LISTAR USUARIOS
# ==========================================================
listar() {

    clear

    echo "=========================================="
    echo "          USUARIOS DEL SISTEMA            "
    echo "=========================================="

    cut -d ":" -f1 /etc/passwd | sort | more

    read -p "Presione ENTER para volver al menú..."
}


# ==========================================================
# BUSCAR USUARIO
# ==========================================================
buscar() {
    clear

    echo "Ingrese apellido y nombre sin espacios:"
    read nombre

    usuario=$(echo "$nombre" | tr '[:upper:]' '[:lower:]')
    cantidad=$(grep -c "^$usuario:" /etc/passwd)

    if [ "$cantidad" -eq 1 ]; then

        echo "El usuario $usuario existe en el sistema."

    else

        echo "El usuario $usuario no existe en el sistema."

    fi

    read -p "Presione ENTER para continuar..."
}


# ==========================================================
# CAMBIAR CONTRASEÑA
# ==========================================================
cambiar_password() {
    clear

    echo "Ingrese apellido y nombre sin espacios:"
    read nombre

    usuario=$(echo "$nombre" | tr '[:upper:]' '[:lower:]')
    cantidad=$(grep -c "^$usuario:" /etc/passwd)

    if [ "$cantidad" -eq 1 ]; then

        echo "Cambio de contraseña para: $usuario"
        passwd "$usuario"

        read -p "Presione ENTER para continuar..."

    else

        echo "El usuario $usuario no existe."
        read -p "Presione ENTER para continuar..."
    fi
}


# ==========================================================
# BLOQUEAR USUARIO
# ==========================================================
bloquear() {
    clear

    echo "Ingrese apellido y nombre sin espacios:"
    read nombre

    usuario=$(echo "$nombre" | tr '[:upper:]' '[:lower:]')
    cantidad=$(grep -c "^$usuario:" /etc/passwd)

    if [ "$cantidad" -eq 1 ]; then

        echo "Bloqueando la cuenta de $usuario..."
        usermod -L "$usuario"

        read -p "Presione ENTER para continuar..."

    else

        echo "El usuario $usuario no existe."
        read -p "Presione ENTER para continuar..."
    fi
}


# ==========================================================
# DESBLOQUEAR USUARIO
# ==========================================================
desbloquear() {
    clear

    echo "Ingrese apellido y nombre sin espacios:"
    read nombre

    usuario=$(echo "$nombre" | tr '[:upper:]' '[:lower:]')
    cantidad=$(grep -c "^$usuario:" /etc/passwd)

    if [ "$cantidad" -eq 1 ]; then

        echo "Desbloqueando la cuenta de $usuario..."
        usermod -U "$usuario"

        read -p "Presione ENTER para continuar..."

    else

        echo "El usuario $usuario no existe."
        read -p "Presione ENTER para continuar..."
    fi
}


# ==========================================================
# EJECUCIÓN DEL PROGRAMA
# ==========================================================
while [ "$opcion" -ne 0 ]; do

    menu_principal

    read -p "Seleccione una opción: " opcion

    case "$opcion" in

        1)
            agregar
            ;;

        2)
            eliminar
            ;;

        3)
            listar
            ;;

        4)
            buscar
            ;;

        5)
            cambiar_password
            ;;

        6)
            bloquear
            ;;

        7)
            desbloquear
            ;;

        0)
            echo "Saliendo del programa..."
            ;;

        *)
            echo "Opción inválida."
            read -p "Presione ENTER para continuar..."
            ;;

    esac

done
