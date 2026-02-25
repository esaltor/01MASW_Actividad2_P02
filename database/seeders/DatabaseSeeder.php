<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Usuario;
use App\Models\TipoRecurso;
use App\Models\Recurso;
use App\Models\Elemento;
use App\Models\TipoIncidencia;
use App\Models\Incidencia;
use App\Models\Adjunto;
use App\Models\AdjuntoElemento;
use App\Models\AdjuntoIncidencia;
use App\Models\Calendario;
use App\Models\Sesion;
use App\Models\Reserva;
use App\Models\Bloqueo;
use App\Models\Notificacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    $this->call([
            CalendarioSeeder::class
     ]);

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $rolUsuario = Rol::create([
            'nombre' => 'Usuario',
            'descripcion' => 'Rol básico de usuario',
        ]);

        $rolAdmin = Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Rol con privilegios',
        ]);

        $rolMia = Rol::create([
            'nombre' => 'Mia',
            'descripcion' => 'Responsable de Medios Informáticos y Audiovisuales',
        ]);

        $admin = Usuario::create([
            'nombre' => 'Admin',
            'apellidos' => 'Demo',
            'telefono' => '123456789',
            'email' => 'admin@demo.com',
            'password' => bcrypt('admin123'),
            'idRol' => $rolAdmin->idRol, // Asignar el rol de Administrador
        ]);

        $usuario = Usuario::create([
            'nombre' => 'Usuario',
            'apellidos' => 'Demo',
            'telefono' => '234567891',
            'email' => 'usuario@demo.com',
            'password' => bcrypt('usuario123'),
            'idRol' => $rolUsuario->idRol, // Asignar el rol de Usuario
        ]);

        $mia = Usuario::create([
            'nombre' => 'Mia',
            'apellidos' => 'Demo',
            'telefono' => '345678912',
            'email' => 'mia@demo.com',
            'password' => bcrypt('mia123'),
            'idRol' => $rolMia->idRol, // Asignar el rol de Mia
        ]);

        TipoRecurso::insert([
            ['nombre'=>'Aula teoría','descripcion'=>'Aula estándar para clases teóricas'],
            ['nombre'=>'Aula informática','descripcion'=>'Aula equipada con ordenadores'],
            ['nombre'=>'Laboratorio','descripcion'=>'Laboratorio de prácticas'],
            ['nombre'=>'Carro portátiles','descripcion'=>'Carro móvil con portátiles'],
            ['nombre'=>'Sala reuniones','descripcion'=>'Sala para reuniones y tutorías'],
        ]);

        Recurso::insert([
            ['nombre'=>'Aula 101','descripcion'=>'Aula de teoría con proyector','ubicacion'=>'Edificio A - Planta 1','estado'=>'DISPONIBLE','caracteristicas'=>'35 plazas, proyector, pizarra blanca','idTipoRecurso'=>1],
            ['nombre'=>'Aula 102','descripcion'=>'Aula de teoría','ubicacion'=>'Edificio A - Planta 1','estado'=>'DISPONIBLE','caracteristicas'=>'30 plazas, pizarra verde','idTipoRecurso'=>1],
            ['nombre'=>'Aula 103','descripcion'=>'Aula de teoría','ubicacion'=>'Edificio A - Planta 1','estado'=>'DISPONIBLE','caracteristicas'=>'30 plazas, pizarra verde','idTipoRecurso'=>1],
            ['nombre'=>'Aula 104','descripcion'=>'Aula de teoría','ubicacion'=>'Edificio A - Planta 1','estado'=>'DISPONIBLE','caracteristicas'=>'30 plazas, pizarra verde','idTipoRecurso'=>1],
            ['nombre'=>'Aula 105','descripcion'=>'Aula de teoría','ubicacion'=>'Edificio A - Planta 1','estado'=>'DISPONIBLE','caracteristicas'=>'30 plazas, pizarra verde','idTipoRecurso'=>1],
            ['nombre'=>'Aula 106','descripcion'=>'Aula de teoría','ubicacion'=>'Edificio A - Planta 1','estado'=>'DISPONIBLE','caracteristicas'=>'30 plazas, pizarra verde','idTipoRecurso'=>1],
            ['nombre'=>'Aula 107','descripcion'=>'Aula de teoría','ubicacion'=>'Edificio A - Planta 1','estado'=>'DISPONIBLE','caracteristicas'=>'30 plazas, pizarra verde','idTipoRecurso'=>1],
            ['nombre'=>'Aula 108','descripcion'=>'Aula de teoría','ubicacion'=>'Edificio A - Planta 1','estado'=>'DISPONIBLE','caracteristicas'=>'30 plazas, pizarra verde','idTipoRecurso'=>1],
            ['nombre'=>'Aula 109','descripcion'=>'Aula de teoría','ubicacion'=>'Edificio A - Planta 1','estado'=>'DISPONIBLE','caracteristicas'=>'30 plazas, pizarra verde','idTipoRecurso'=>1],
            ['nombre'=>'Aula Info 1','descripcion'=>'Aula informática básica','ubicacion'=>'Edificio B - Planta 2','estado'=>'DISPONIBLE','caracteristicas'=>'25 PCs, proyector','idTipoRecurso'=>2],
            ['nombre'=>'Aula Info 2','descripcion'=>'Aula informática avanzada','ubicacion'=>'Edificio B - Planta 3','estado'=>'MANTENIMIENTO','caracteristicas'=>'30 PCs, doble proyector','idTipoRecurso'=>2],
            ['nombre'=>'Aula Info 3','descripcion'=>'Aula informática básica','ubicacion'=>'Edificio B - Planta 2','estado'=>'DISPONIBLE','caracteristicas'=>'25 PCs, proyector','idTipoRecurso'=>2],
            ['nombre'=>'Aula Info 4','descripcion'=>'Aula informática básica','ubicacion'=>'Edificio B - Planta 3','estado'=>'MANTENIMIENTO','caracteristicas'=>'25 PCs, proyector','idTipoRecurso'=>2],
            ['nombre'=>'Aula Info 5','descripcion'=>'Aula informática avanzada','ubicacion'=>'Edificio B - Planta 2','estado'=>'DISPONIBLE','caracteristicas'=>'30 PCs, doble proyector','idTipoRecurso'=>2],
            ['nombre'=>'Lab Química','descripcion'=>'Laboratorio de química','ubicacion'=>'Edificio C - Planta 0','estado'=>'DISPONIBLE','caracteristicas'=>'Campanas, material de química','idTipoRecurso'=>3],
            ['nombre'=>'Carro Portátiles 1','descripcion'=>'Carro con 20 portátiles','ubicacion'=>'Almacén TIC','estado'=>'DISPONIBLE','caracteristicas'=>'20 portátiles, WiFi','idTipoRecurso'=>4],
            ['nombre'=>'Carro Portátiles 2','descripcion'=>'Carro con 30 portátiles','ubicacion'=>'Almacén TIC','estado'=>'DISPONIBLE','caracteristicas'=>'30 portátiles, WiFi','idTipoRecurso'=>4],
            ['nombre'=>'Carro Portátiles 3','descripcion'=>'Carro con 30 portátiles','ubicacion'=>'Almacén TIC','estado'=>'DISPONIBLE','caracteristicas'=>'30 portátiles, WiFi','idTipoRecurso'=>4],
            ['nombre'=>'Carro Portátiles 4','descripcion'=>'Carro con 20 portátiles','ubicacion'=>'Almacén TIC','estado'=>'DISPONIBLE','caracteristicas'=>'20 portátiles, WiFi','idTipoRecurso'=>4],
            ['nombre'=>'Sala Reuniones 1','descripcion'=>'Sala pequeña para tutorías','ubicacion'=>'Edificio A - Planta 2','estado'=>'DISPONIBLE','caracteristicas'=>'8 plazas, pantalla TV','idTipoRecurso'=>5],
            ['nombre'=>'Sala Reuniones 2','descripcion'=>'Sala grande para reuniones','ubicacion'=>'Edificio A - Planta 2','estado'=>'DISPONIBLE','caracteristicas'=>'10 plazas, pantalla TV','idTipoRecurso'=>5],
        ]);

        Elemento::insert([
            ['nombre'=>'Proyector Aula 101','descripcion'=>'Proyector Epson','estado'=>'Operativo','idRecurso'=>1],
            ['nombre'=>'Pizarra Aula 101','descripcion'=>'Pizarra blanca magnética','estado'=>'Operativa','idRecurso'=>1],
            ['nombre'=>'Proyector Aula 102','descripcion'=>'Proyector LG','estado'=>'Operativo','idRecurso'=>2],
            ['nombre'=>'Pizarra Aula 102','descripcion'=>'Pizarra verde','estado'=>'Operativa','idRecurso'=>2],
            ['nombre'=>'Proyector Aula 103','descripcion'=>'Proyector LG','estado'=>'Operativo','idRecurso'=>3],
            ['nombre'=>'Pizarra Aula 103','descripcion'=>'Pizarra verde','estado'=>'Operativa','idRecurso'=>3],
        ]);

        Elemento::insert([
            ['nombre'=>'Proyector Aula 101','descripcion'=>'Proyector Epson','estado'=>'Operativo','idRecurso'=>1],
            ['nombre'=>'Pizarra Aula 101','descripcion'=>'Pizarra blanca magnética','estado'=>'Operativa','idRecurso'=>1],
            ['nombre'=>'Proyector Aula 102','descripcion'=>'Proyector LG','estado'=>'Operativo','idRecurso'=>2],
            ['nombre'=>'Pizarra Aula 102','descripcion'=>'Pizarra verde','estado'=>'Operativa','idRecurso'=>2],
            ['nombre'=>'Proyector Aula 103','descripcion'=>'Proyector LG','estado'=>'Operativo','idRecurso'=>3],
            ['nombre'=>'Pizarra Aula 103','descripcion'=>'Pizarra verde','estado'=>'Operativa','idRecurso'=>3],
        ]);

        TipoIncidencia::insert([
            ['nombre'=>'Avería','descripcion'=>'Fallo técnico en el recurso'],
            ['nombre'=>'Mantenimiento','descripcion'=>'Revisión programada o preventiva'],
            ['nombre'=>'Suministro','descripcion'=>'Problemas con consumibles o materiales necesarios'],
            ['nombre'=>'Limpieza','descripcion'=>'Incidencia relacionada con limpieza o higiene del recurso'],
            ['nombre'=>'Reserva','descripcion'=>'Conflictos o errores en la reserva del recurso'],
            ['nombre'=>'Actualización','descripcion'=>'Necesidad de actualizar software o equipamiento'],
            ['nombre'=>'Seguridad','descripcion'=>'Incidencia relacionada con riesgos de seguridad'],
        ]);

        Incidencia::insert([
            [
                'titulo'=>'Proyector no funciona Aula 101',
                'descripcion'=>'El proyector Epson de Aula 101 no enciende',
                'estado'=>'Abierta',
                'idTipoIncidencia'=>1,
                'idElemento'=>1,
                'idUsuario'=>$admin->idUsuario,
            ],
            [
                'titulo'=>'Pizarra blanca dañada Aula 101',
                'descripcion'=>'La pizarra blanca magnética tiene manchas difíciles de borrar',
                'estado'=>'Abierta',
                'idTipoIncidencia'=>4,
                'idElemento'=>2,
                'idUsuario'=>$usuario->idUsuario,
            ],
        ]);

        Adjunto::insert([
            ['nombre'=>'Ordenador Aula Info','mimeType'=>'image/jpeg','tamBytes'=>204800,'url'=>'https://img.freepik.com/foto-gratis/pantalla-pc-escritorio-oficina-inicio-que-muestra-lenguajes-programacion_482257-120128.jpg'],
            ['nombre'=>'Proyector Sala Reuniones','mimeType'=>'image/jpeg','tamBytes'=>204800,'url'=>'https://img.freepik.com/foto-gratis/tomada-panoramica-oficina-vacia-proyector-medio-escritorio-conferencias_1098-19956.jpg'],
            ['nombre'=>'Pantalla Sala Reuniones','mimeType'=>'image/jpeg','tamBytes'=>204800,'url'=>'https://img.freepik.com/foto-gratis/pantalla-proyeccion-negra-conferencia-telefonica-mujer-negocios-afroamericana-oficina_637285-12915.jpg'],
            ['nombre'=>'Mesa Sala Reuniones','mimeType'=>'image/jpeg','tamBytes'=>204800,'url'=>'https://img.freepik.com/foto-gratis/silla-oficina-naturaleza-muerta_23-2151149120.jpg'],
            ['nombre'=>'Pizarra Blanca','mimeType'=>'image/jpeg','tamBytes'=>204800,'url'=>'https://img.freepik.com/foto-gratis/tabla-blanca-oficina-perspectiva-cercana_1153-3801.jpg'],
        ]);

        AdjuntoElemento::insert([
            ['idAdjunto'=>1,'idElemento'=>1],
            ['idAdjunto'=>2,'idElemento'=>6],
            ['idAdjunto'=>3,'idElemento'=>3],
            ['idAdjunto'=>4,'idElemento'=>5],
            ['idAdjunto'=>5,'idElemento'=>2],
        ]);

        AdjuntoIncidencia::insert([
            ['idAdjunto'=>1,'idIncidencia'=>1],
            ['idAdjunto'=>2,'idIncidencia'=>2]
        ]);

        Calendario::insert([
            ['fecha'=>'2025-11-17'],
            ['fecha'=>'2025-11-18'],
            ['fecha'=>'2025-11-19'],
        ]);

        Sesion::insert([
            ['horaInicio'=>'08:15','horaFin'=>'09:05'],
            ['horaInicio'=>'09:10','horaFin'=>'10:00'],
            ['horaInicio'=>'10:05','horaFin'=>'10:55'],
            ['horaInicio'=>'10:55','horaFin'=>'11:25'],
            ['horaInicio'=>'11:25','horaFin'=>'12:15'],
            ['horaInicio'=>'12:20','horaFin'=>'13:10'],
            ['horaInicio'=>'13:15','horaFin'=>'14:05'],
            ['horaInicio'=>'14:00','horaFin'=>'15:00'],
            ['horaInicio'=>'15:00','horaFin'=>'16:00'],
            ['horaInicio'=>'16:00','horaFin'=>'17:00'],
            ['horaInicio'=>'17:00','horaFin'=>'18:00'],
            ['horaInicio'=>'19:00','horaFin'=>'20:00'],
        ]);

        Reserva::insert([
            ['estado'=>'Confirmada','fecha'=>'2025-11-17','idSesion'=>4,'idUsuario'=>$usuario->idUsuario,'idRecurso'=>4],
            ['estado'=>'Pendiente','fecha'=>'2025-11-17','idSesion'=>5,'idUsuario'=>$mia->idUsuario,'idRecurso'=>5],
            ['estado'=>'Confirmada','fecha'=>'2025-11-17','idSesion'=>6,'idUsuario'=>$admin->idUsuario,'idRecurso'=>6],
        ]);

        Bloqueo::insert([
            ['idRecurso'=>1,'diaSemana'=>1,'idSesion'=>1,'motivoBloqueo'=>'Mantenimiento del aula'],
            ['idRecurso'=>1,'diaSemana'=>3,'idSesion'=>3,'motivoBloqueo'=>'Reunión de departamento'],
        ]);

        Notificacion::insert([
            ['asunto'=>'Reserva confirmada - Aula 101','cuerpo'=>'Tu reserva del recurso "Aula 101" ha sido confirmada para el día 2025-03-10 en la sesión 2.','canal'=>'EMAIL','enviadaEn'=>'2025-03-01 09:15:00','idUsuario'=>$mia->idUsuario],
            ['asunto'=>'Reserva rechazada - Aula Info 2','cuerpo'=>'La reserva del recurso "Aula Info 2" ha sido rechazada por mantenimiento.','canal'=>'EMAIL','enviadaEn'=>'2025-03-02 10:30:00','idUsuario'=>$admin->idUsuario],
        ]);
    }
}
