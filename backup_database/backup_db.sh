#!/bin/bash

# Configuración de la copia de seguridad
BACKUP_DIR="/var/www/html/Mercatavico/backup_database/"
DATE=$(date +"%Y-%m-%d")
BACKUP_NAME="bd-$DATE.sql"

# Crear el directorio de copia de seguridad si no existe
if [ ! -d "$BACKUP_DIR" ]
then
    mkdir -p "$BACKUP_DIR"
fi

# Hacer la copia de seguridad
mysqldump --defaults-file=/home/mercatavico/.my.cnf Mercatavico > "$BACKUP_DIR/$BACKUP_NAME"


# Comprobar si la copia de seguridad se ha creado correctamente
if [ -f "$BACKUP_DIR/$BACKUP_NAME" ]
then
    echo "La copia de seguridad se ha creado correctamente en: $BACKUP_DIR/$BACKUP_NAME"
else
    echo "Ha ocurrido un error al crear la copia de seguridad."
fi
