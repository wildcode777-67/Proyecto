#!/bin/bash

# ==============================
# GESTIÓN DE FIREWALL - FIREWALLD
# ==============================

opcion=67
hoy=$(date +"%d-%m-%Y")

pausa() {
    read -p "Presione ENTER para continuar..." _
}

mostrar_menu() {
    clear
    echo "-----------------------------------------"
    echo "        ADMINISTRACIÓN DE FIREWALL"
    echo "-----------------------------------------"
    echo "Fecha: $hoy"
    echo
    echo "1) Consultar estado del FirewallD"
    echo "2) Habilitar HTTP y HTTPS"
    echo "3) Bloquear una IP"
    echo "4) Activar política DROP"
    echo "5) Permitir PING"
    echo "6) Mostrar servicios habilitados"
    echo "7) Bloquear una MAC"
    echo "8) Incorporar un servicio"
    echo "0) Finalizar"
    echo "-----------------------------------------"
}

estado_firewall() {
    clear
    echo "=== ESTADO DEL FIREWALL ==="
    firewall-cmd --state
    echo
    pausa
}

habilitar_web() {
    clear
    echo "=== HABILITAR SERVICIOS WEB ==="

    firewall-cmd --permanent --add-service=http
    firewall-cmd --permanent --add-service=https
    firewall-cmd --reload

    echo
    echo "HTTP y HTTPS fueron habilitados."
    pausa
}

bloquear_ip() {
    clear
    echo "=== BLOQUEAR DIRECCIÓN IP ==="
    read -p "Ingrese la IP que desea bloquear: " direccion_ip

    if [ -z "$direccion_ip" ]; then
        echo "ERROR: no se ingresó ninguna IP."
    else
        firewall-cmd --permanent \
            --add-rich-rule="rule family='ipv4' source address='$direccion_ip' drop"
        firewall-cmd --reload
        echo "La IP $direccion_ip fue agregada a las reglas de bloqueo."
    fi

    pausa
}

activar_drop() {
    clear
    echo "=== POLÍTICA RESTRICTIVA ==="
    echo "La zona predeterminada pasará a ser 'drop'."
    echo "El tráfico no permitido explícitamente será descartado."

    firewall-cmd --set-default-zone=drop

    echo
    echo "Política aplicada."
    pausa
}

permitir_ping() {
    clear
    echo "=== PERMITIR PING ==="

    firewall-cmd --permanent \
        --add-rich-rule='rule protocol value="icmp" accept'
    firewall-cmd --reload

    echo "Las solicitudes ICMP/PING están permitidas."
    pausa
}

ver_servicios() {
    clear
    echo "=== SERVICIOS PERMITIDOS ==="

    zona_actual=$(firewall-cmd --get-default-zone)
    echo "Zona predeterminada: $zona_actual"
    echo
    firewall-cmd --list-services

    echo
    pausa
}

bloquear_mac() {
    clear
    echo "=== BLOQUEAR DIRECCIÓN MAC ==="
    read -p "Ingrese la dirección MAC: " direccion_mac

    if [ -z "$direccion_mac" ]; then
        echo "ERROR: no se ingresó ninguna dirección MAC."
    else
        firewall-cmd --permanent \
            --add-rich-rule="rule source mac='$direccion_mac' drop"
        firewall-cmd --reload
        echo "La MAC $direccion_mac fue bloqueada."
    fi

    pausa
}

agregar_servicio() {
    clear
    echo "=== AGREGAR SERVICIO ==="
    read -p "Nombre del servicio (ej. mysql, mariadb, dns): " nombre_servicio

    if [ -z "$nombre_servicio" ]; then
        echo "ERROR: el nombre del servicio no puede quedar vacío."
    elif firewall-cmd --permanent --add-service="$nombre_servicio" 2>/dev/null; then
        firewall-cmd --reload
        echo "OK: el servicio '$nombre_servicio' fue agregado."
    else
        echo "ERROR: '$nombre_servicio' no corresponde a un servicio válido de FirewallD."
    fi

    echo
    pausa
}

# ==============================
# PROGRAMA PRINCIPAL
# ==============================

while [ "$opcion" -ne 0 ]; do
    mostrar_menu
    read -p "Seleccione una opción: " opcion

    case "$opcion" in
        1) estado_firewall ;;
        2) habilitar_web ;;
        3) bloquear_ip ;;
        4) activar_drop ;;
        5) permitir_ping ;;
        6) ver_servicios ;;
        7) bloquear_mac ;;
        8) agregar_servicio ;;
        0)
            echo "Programa finalizado."
            ;;
        *)
            echo "ERROR: opción inválida. Seleccione un número del 0 al 8."
            pausa
            ;;
    esac
done
