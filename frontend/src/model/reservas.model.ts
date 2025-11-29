export interface Reserva {
    id?: number;
    fecha: string;
    numero_asistentes: number;
    total: number;
    estado?: 'Cancelada' | 'Con Adelanto' | 'Por Pagar';
    id_cliente: number;
    id_pagos: number;
    id_evento: number;
    id_ubicacion: number;
}