create database proyecto_eventos_c;
use proyecto_eventos_c;

create table usuario(
	id_usuario int primary key auto_increment,
	nombre_completo varchar(100) not null,
	email varchar(100) not null unique,
    password_hash varchar(150) not null,
    rol varchar(150) default ('Cliente'),
    
    tarjeta varchar(16) null unique,
    fecha_vencimiento date null
);

create table sede(
	id_sede int primary key auto_increment,
    nombre varchar(100) not null,
    capacidad int not null,
    direccion varchar(150) not null,
    precio_base decimal(10,2) not null default 0.00
);

create table proveedor(
	id_proveedor int primary key auto_increment,
    nombre_contacto varchar(100),
    nombre_empresa varchar(150),
    direccion varchar(150),
    telefono varchar(12)
);

create table recurso(
	id_recurso int primary key auto_increment,
    id_proveedor int,
    nombre_recurso varchar(100),
    descripcion varchar(150),
    costounidad decimal(10,2),
    stock int,
    
    foreign key (id_proveedor) references proveedor(id_proveedor)
);

create table servicio(
	id_servicio int primary key auto_increment,
    id_proveedor int,
    nombre_servicio varchar(150),
    costo decimal(10,2),
    
    foreign key (id_proveedor) references proveedor(id_proveedor)
);

create table tipo_evento(
    id_tipo_evento int primary key auto_increment,
    nombre_tipo varchar(100) not null,
    descripcion varchar(200)
);

create table evento(
	id_evento int primary key auto_increment,
    id_cliente int,
    id_sede int,
    id_tipo_evento int, /* Nueva Relación */
    nombre_evento varchar(100) not null,
    fecha_evento date,
    hora_inicio time,
    hora_fin time,
    estado enum ('Borrador', 'Confirmado', 'Cancelado') default ('Borrador'),
    
    foreign key (id_cliente) references usuario(id_usuario),
    foreign key (id_sede) references sede(id_sede),
    foreign key (id_tipo_evento) references tipo_evento(id_tipo_evento)
);

create table reserva(
	id_reserva int primary key auto_increment,
    id_evento int,
    fecha_reserva timestamp,
    costo_total decimal(10,2),
    monto_pagado decimal(10,2),
    estado_pago enum ('Pendiente', 'Parcial', 'Pagado') default ('Pendiente'),
    
    foreign key (id_evento) references evento(id_evento)
);

create table detalle_recurso(
	id_evento int,
    id_recurso int,
    cantidad int,
    
    primary key (id_evento, id_recurso),
    foreign key (id_evento) references evento(id_evento),
    foreign key (id_recurso) references recurso(id_recurso)
);

create table detalle_servicio(
	id_evento int,
    id_servicio int,
    cantidad int default 1,
    
    primary key (id_evento, id_servicio),
    foreign key (id_evento) references evento(id_evento),
    foreign key (id_servicio) references servicio(id_servicio)
);


/* ==========================================================================
   SECCION DE INSERCIONES DE DATOS DE PRUEBA (Más que nada para que funcione en
   la revisión... Tenga cuidado con qué se edita COMPAREEEEE)
   ========================================================================== */

/* --- 10 TIPOS DE EVENTO --- */
INSERT INTO tipo_evento (nombre_tipo, descripcion) VALUES
('Boda', 'Ceremonia y recepción nupcial'),
('XV Años', 'Fiesta de quinceañera tradicional o moderna'),
('Cumpleaños', 'Celebración de cumpleaños para todas las edades'),
('Reunión Corporativa', 'Eventos empresariales, conferencias o seminarios'),
('Aniversario', 'Celebración de aniversarios de bodas o empresas'),
('Graduación', 'Fiesta de graduación escolar o universitaria'),
('Bautizo', 'Celebración religiosa y reunión familiar'),
('Baby Shower', 'Fiesta de bienvenida para bebés'),
('Concierto Privado', 'Evento musical exclusivo'),
('Exposición de Arte', 'Galería y cóctel de presentación');


/* --- 12 SEDES --- */
INSERT INTO sede (nombre, capacidad, direccion, precio_base) VALUES 
('Gran Salón Majestic', 500, 'Av. Principal 123, Centro', 1500.00),
('Jardín Botánico El Edén', 300, 'Calle Las Flores 45, Norte', 2200.00),
('Terraza Sky View', 150, 'Torre Alta Piso 20, Financiero', 1200.00),
('Hacienda La Toscana', 800, 'Km 15 Vía al Valle', 3500.00),
('Auditorio Corporativo Sigma', 200, 'Blvd. Empresarial 88, Sur', 800.00),
('Salón de Eventos Cristal', 400, 'Calle de Vidrio 99, Centro', 1800.00),
('Playa Privada Azure', 600, 'Costa Azul Km 5', 4000.00),
('Espacio Industrial Loft', 100, 'Zona Fabril Lote 4', 950.00),
('Mansión Colonial Heritage', 250, 'Plaza Histórica 5', 2800.00),
('Rooftop Estrella', 80, 'Hotel Lux 5ta Avenida', 1600.00),
('Centro de Convenciones Nexus', 1000, 'Av. Convenciones 100', 5000.00),
('Club Campestre Los Pinos', 450, 'Salida Norte Km 2', 1300.00);


/* --- 20 PROVEEDORES --- */
INSERT INTO proveedor (nombre_contacto, nombre_empresa, direccion, telefono) VALUES
('Carlos Ruiz', 'Banquetes Delicia Real', 'Av. Gastronómica 10', '555-0101'),
('Ana Gómez', 'Seguridad Cobra', 'Calle Blindada 22', '555-0102'),
('Luis Pineda', 'Sonido Thunder Pro', 'Av. Acústica 33', '555-0103'),
('Sofia Torres', 'Decoraciones ArteFlor', 'Paseo de las Rosas 44', '555-0104'),
('Jorge M.', 'Licores El Manantial', 'Bodega Central 5', '555-0105'),
('Elena V.', 'TechVision Audiovisuales', 'Parque Tecnológico 8', '555-0106'),
('Roberto S.', 'Limpieza Pura y Total', 'Calle Limpia 12', '555-0107'),
('Maria L.', 'Mobiliario Confort VIP', 'Av. Muebles 9', '555-0108'),
('Pedro J.', 'Flores del Valle', 'Mercado de Flores Puesto 2', '555-0109'),
('Lucia F.', 'Iluminación Rayo Laser', 'Calle Voltaje 11', '555-0110'),
('Miguel A.', 'Catering Gourmet Express', 'Cocinas Industriales 3', '555-0111'),
('Ricardo T.', 'Seguridad Falcón', 'Torre Vigía 1', '555-0112'),
('DJ Alex', 'Master Mix DJs', 'Estudio de Grabación 4', '555-0113'),
('Patricia B.', 'Toldos y Carpas EventPro', 'Almacén General 7', '555-0114'),
('Carmen D.', 'Mantelería Fina Imperial', 'Textiles del Sur 6', '555-0115'),
('Juan C.', 'Logística Rápida', 'Transportes Unidos 2', '555-0116'),
('Laura M.', 'Fotografía Captura Eterna', 'Estudio Fotográfico A', '555-0117'),
('Diego R.', 'Video CinemaPro', 'Estudio de Cine B', '555-0118'),
('Esteban Q.', 'ServiStaff Personal', 'Agencia de Empleo 9', '555-0119'),
('Valeria K.', 'Animación FiestaTotal', 'Teatro Local 1', '555-0120');


/* --- 45 RECURSOS (Stock 40-120) --- */
/* Sillas y Mesas (Prov 8) */
INSERT INTO recurso (id_proveedor, nombre_recurso, descripcion, costounidad, stock) VALUES
(8, 'Silla Tiffany Blanca', 'Silla elegante de resina blanca', 5.00, 120),
(8, 'Silla Tiffany Dorada', 'Silla elegante color oro', 6.00, 100),
(8, 'Silla Plegable Básica', 'Silla de plástico resistente', 2.00, 120),
(8, 'Silla Avant Garde', 'Silla moderna de madera', 8.00, 80),
(8, 'Mesa Redonda (10 pax)', 'Mesa de 1.5m de diámetro', 15.00, 50),
(8, 'Mesa Tablón (10 pax)', 'Mesa rectangular', 15.00, 60),
(8, 'Mesa Imperial', 'Mesa larga de lujo', 25.00, 40),
(8, 'Sillón Lounge Blanco', 'Módulo individual', 20.00, 45);

/* Mantelería (Prov 15) */
INSERT INTO recurso (id_proveedor, nombre_recurso, descripcion, costounidad, stock) VALUES
(15, 'Mantel Blanco Redondo', 'Tela poliéster', 8.00, 100),
(15, 'Mantel Negro Redondo', 'Tela poliéster', 8.00, 100),
(15, 'Camino de Mesa Dorado', 'Tela satinada', 3.00, 120),
(15, 'Servilleta de Tela', 'Algodón blanco', 1.00, 120),
(15, 'Cubre Silla Blanco', 'Tela spandex', 2.00, 120);

/* Cristalería y Vajilla (Prov 1) */
INSERT INTO recurso (id_proveedor, nombre_recurso, descripcion, costounidad, stock) VALUES
(1, 'Plato Base Plata', 'Bajo plato decorativo', 2.00, 120),
(1, 'Copa de Vino Tinto', 'Cristal fino', 1.50, 120),
(1, 'Copa de Champán', 'Tipo flauta', 1.50, 110),
(1, 'Vaso Highball', 'Para tragos largos', 1.00, 120),
(1, 'Set Cubiertos plata', 'Tenedor, cuchillo, cuchara', 3.00, 120);

/* Iluminación y Sonido (Prov 3 y 10) */
INSERT INTO recurso (id_proveedor, nombre_recurso, descripcion, costounidad, stock) VALUES
(10, 'Foco LED Par 64', 'Iluminación ambiental RGB', 15.00, 50),
(10, 'Cabeza Móvil Beam', 'Luz robótica para pista', 35.00, 40),
(10, 'Barra LED UV', 'Luz negra para neón', 20.00, 40),
(10, 'Guirnalda de Luces (10m)', 'Estilo vintage', 12.00, 60),
(3, 'Micrófono Inalámbrico', 'Shure SM58', 25.00, 40),
(3, 'Parlante Activo 15"', 'JBL EON', 50.00, 40),
(3, 'Soporte de Parlante', 'Trípode metálico', 5.00, 45);

/* Decoración (Prov 4 y 9) */
INSERT INTO recurso (id_proveedor, nombre_recurso, descripcion, costounidad, stock) VALUES
(4, 'Centro de Mesa Floral', 'Arreglo artificial alto', 18.00, 50),
(4, 'Candelabro 5 Brazos', 'Metal dorado', 22.00, 40),
(4, 'Alfombra Roja (10m)', 'Para entrada', 40.00, 40),
(4, 'Biombo Decorativo', 'Separador de ambientes', 30.00, 40),
(9, 'Arreglo Floral Natural', 'Rosas y Lirios frescos', 45.00, 60),
(9, 'Pared de Flores', 'Backdrop para fotos 2x2m', 150.00, 40);

/* Varios (Prov 5, 6, 14) */
INSERT INTO recurso (id_proveedor, nombre_recurso, descripcion, costounidad, stock) VALUES
(5, 'Hielera Metálica', 'Acero inoxidable', 5.00, 80),
(5, 'Dispensador de Bebidas', 'Vidrio 5L', 10.00, 60),
(14, 'Carpa 5x5m', 'Lona blanca impermeable', 120.00, 40),
(14, 'Calefactor de Patio', 'Gas propano incluido', 60.00, 40),
(6, 'Proyector HD', '3000 Lúmenes', 50.00, 40),
(6, 'Pantalla de Proyección', 'Trípode 2x2m', 20.00, 45),
(6, 'Televisor LED 50"', 'Soporte de piso incluido', 80.00, 40);

/* Más sillas para llegar a 45 items */
INSERT INTO recurso (id_proveedor, nombre_recurso, descripcion, costounidad, stock) VALUES
(8, 'Silla Fantasma (Ghost)', 'Acrílico transparente', 9.00, 90),
(8, 'Banco Alto de Barra', 'Metal y cuero', 12.00, 50),
(20, 'Máquina de Humo', 'Efecto niebla baja', 35.00, 40),
(20, 'Pista de Baile LED', 'Módulo 1x1m', 45.00, 80);


/* --- 10 SERVICIOS --- */
INSERT INTO servicio (id_proveedor, nombre_servicio, costo) VALUES
(11, 'Catering Premium (3 Tiempos)', 1500.00),
(11, 'Buffet Internacional', 1200.00),
(5, 'Barra Libre Nacional (5 Horas)', 800.00),
(5, 'Barra Libre Premium (5 Horas)', 1400.00),
(2, 'Seguridad VIP (3 Guardias)', 450.00),
(13, 'DJ + Sonido Básico (5 Horas)', 600.00),
(13, 'DJ + Sonido + Iluminación Pista', 1100.00),
(17, 'Fotografía Profesional (Cobertura Total)', 900.00),
(18, 'Video Cinematic con Drone', 1300.00),
(20, 'Hora Loca (2 Animadores + Cotillón)', 350.00);
