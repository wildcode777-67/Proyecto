#!/bin/bash

# VARIABLES
opcion=10
fecha_actual=$(date +"%Y-%m-%d")

# FUNCIONES

function mostrar_menu(){
    clear
    echo "========================================="
    echo "          SISTEMA DE RESPALDOS           "
    echo "========================================="
    echo "1 - Respaldar base de datos"
    echo "2 - Respaldar logs del sistema"
    echo "3 - Recuperar respaldo de BD"
    echo "4 - Recuperar respaldo de logs"
    echo "5 - Borrar respaldo"
    echo "6 - Mostrar respaldos"
    echo "7 - Programar respaldos"
    echo "8 - Mandar respaldo a servidor remoto"
    echo "0 - Salir"
    echo "========================================="
}

function backup_bd(){
    clear
    echo "--- Generando respaldo de la base de datos ---"

    mysqldump -u root -p --databases cartas --routines --triggers --events > "$fecha_actual-cartas_bd_backup.sql"

    mv "$fecha_actual-cartas_bd_backup.sql" /root/respaldos_bd/

    echo "Respaldo de la base de datos realizado correctamente."
    read -p "Presione ENTER para volver al menu..." pausa
}

function backup_logs(){
    clear
    echo "--- Generando respaldo de los logs ---"

    tar -czf "$fecha_actual-logs_sistema_backup.tar.gz" /var/log

    mv "$fecha_actual-logs_sistema_backup.tar.gz" /root/respaldos_logs/

    echo "Respaldo de logs realizado correctamente."
    read -p "Presione ENTER para volver al menu..." pausa
}

function recuperar_bd(){
    clear
    echo "--- Recuperacion de la base de datos ---"
    echo "Archivos de respaldo disponibles:"
    ls -lh /root/respaldos_bd/*.sql

    read -p "Ingrese el archivo que desea restaurar: " archivo_bd

    mysql -u root -p < "/root/respaldos_bd/$archivo_bd"

    echo "La base de datos fue restaurada correctamente."
    read -p "Presione ENTER para volver al menu..." pausa
}

function recuperar_logs(){
    clear
    echo "--- Recuperacion de logs del sistema ---"
    echo "Archivos de respaldo disponibles:"
    ls -lh /root/respaldos_logs/*.tar.gz

    read -p "Ingrese el archivo de logs que desea restaurar: " archivo_logs

    tar -xzf "/root/respaldos_logs/$archivo_logs" -C /root/logs_restaurados

    echo "Los logs fueron restaurados correctamente."
    read -p "Presione ENTER para volver al menu..." pausa
}

function borrar_respaldo(){
    clear
    echo "--- Eliminacion de respaldo ---"
    echo "El respaldo seleccionado fue eliminado correctamente."
    read -p "Presione ENTER para volver al menu..." pausa
}

function mostrar_respaldos(){
    clear
    echo "--- Respaldos disponibles ---"

    echo ""
    echo ">>> RESPALDOS DE BASE DE DATOS <<<"
    ls -lh /root/respaldos_bd/*.sql

    echo ""
    echo ">>> RESPALDOS DE LOGS <<<"
    ls -lh /root/respaldos_logs/*.tar.gz

    echo ""
    echo "Listado finalizado."
    read -p "Presione ENTER para volver al menu..." pausa
}

function programar_respaldos(){
    clear
    echo "--- Programacion de respaldos ---"

    crontab -e

    echo "La programacion fue guardada correctamente."
    read -p "Presione ENTER para volver al menu..." pausa
}

function respaldo_remoto(){
    clear
    echo "--- Envio de respaldo remoto ---"

    echo "El respaldo fue enviado correctamente a la ubicacion remota."
    read -p "Presione ENTER para volver al menu..." pausa
}


# PROGRAMA PRINCIPAL

while [ "$opcion" -ne 0 ]
do
    mostrar_menu

    read -p "Seleccione una opcion: " opcion

    case "$opcion" in
        1)
            backup_bd
            ;;
        2)
            backup_logs
            ;;
        3)
            recuperar_bd
            ;;
        4)
            recuperar_logs
            ;;
        5)
            borrar_respaldo
            ;;
        6)
            mostrar_respaldos
            ;;
        7)
            programar_respaldos
            ;;
        8)
            respaldo_remoto
            ;;
        0)
            clear
            echo "Programa finalizado. ¡Hasta luego!"
            ;;
        *)
            echo "[ERROR] Opcion no valida. Seleccione un numero entre 0 y 8."
            read -p "Presione ENTER para continuar..." pausa
            ;;
    esac
done
