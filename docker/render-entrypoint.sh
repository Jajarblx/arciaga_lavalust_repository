#!/bin/sh
set -eu

if [ -z "${PORT:-}" ]; then
    echo "PORT must be set." >&2
    exit 1
fi

case "$PORT" in
    *[!0-9]*)
        echo "PORT must be a numeric TCP port." >&2
        exit 1
        ;;
esac

if [ "$PORT" -lt 1 ] || [ "$PORT" -gt 65535 ]; then
    echo "PORT must be between 1 and 65535." >&2
    exit 1
fi

# Render terminates HTTPS at its proxy. Apache listens internally on the exact
# port assigned to this service and accepts connections on every IPv4 address.
printf 'Listen 0.0.0:%s\n' "$PORT" > /etc/apache2/ports.conf
sed -ri "s#<VirtualHost \*:[0-9]+>#<VirtualHost *:${PORT}>#" \
    /etc/apache2/sites-available/000-default.conf

exec "$@"
