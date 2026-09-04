#!/bin/bash

function agregar_grupo(){
clear

echo "Ingrese el nombre del grupo: "
read grupo

grupo=$(echo $grupo | tr [:upper:] [:lower:])

if [ $(getent group $grupo) ]; then
    echo "El grupo $grupo ya existe en el sistema"

    echo "El usuario $USER en la fecha $(date +%Y-%m-%d-%H:%M:%S) intentó agregar el grupo $grupo, pero ya existe."

    read pausa

else
    groupadd $grupo

    if [ $? -eq 0 ]; then
        echo "Grupo $grupo agregado correctamente"
        echo "El usuario $USER en la fecha $(date +%Y-%m-%d-%H:%M:%S) agregó el grupo $grupo."
    else
        echo "Error al agregar el grupo $grupo"
    fi

    read pausa
fi
}


function borrar_grupo(){
clear

echo "Ingrese el nombre del grupo a eliminar: "
read grupo

grupo=$(echo $grupo | tr [:upper:] [:lower:])

if [ $(getent group $grupo) ]; then

    echo "El grupo $grupo será eliminado del sistema, ¿está seguro? [S/N]"
    read letra

    if [ $letra == 'S' -o $letra == 's' ]; then

        groupdel $grupo

        if [ $? -eq 0 ]; then
            echo "Grupo $grupo eliminado del sistema"
            echo "$(date +%Y-%m-%d-%H:%M:%S) Grupo: $grupo eliminado del sistema"
        else
            echo "Error al eliminar el grupo $grupo"
        fi

        read pausa

    else
        echo "Operación cancelada, presione enter para volver al menú principal"
        read pausa
    fi

else
    echo "El grupo $grupo no existe"
    read pausa
fi
}


function listar_grupos(){
clear

echo "========================================="
echo "          GRUPOS DEL SISTEMA             "
echo "========================================="

cut -d ":" -f 1 /etc/group | sort | more

echo "Presione enter para volver al menú principal"
read pausa
}


function buscar_grupo(){
clear

echo "Ingrese el nombre del grupo a buscar: "
read grupo

grupo=$(echo $grupo | tr [:upper:] [:lower:])

if [ $(getent group $grupo) ]; then
    echo "El grupo $grupo existe en el sistema"
else
    echo "El grupo $grupo no existe en el sistema"
fi

echo "Presione enter para volver al menú principal"
read pausa
}


function menu(){
clear

echo "========================================="
echo "       MENÚ DE GESTIÓN DE GRUPOS         "
echo "========================================="
echo "1 - Agregar grupo"
echo "2 - Borrar grupo"
echo "3 - Listar grupos del sistema"
echo "4 - Buscar un grupo en el sistema"
echo "0 - Salir"
echo "========================================="
}


# PROGRAMA PRINCIPAL

opc=10

while [ $opc -ne 0 ]
do

    menu

    read -p "Ingrese la opción correspondiente: " opc

    case $opc in

        1)
            agregar_grupo
            ;;

        2)
            borrar_grupo
            ;;

        3)
            listar_grupos
            ;;

        4)
            buscar_grupo
            ;;

        0)
            echo "Saliendo del menú de gestión de grupos"
            ;;

        *)
            echo "Seleccionó una opción incorrecta"
            read pausa
            ;;

    esac

done
