    @extends('principal')
    @section('contenido')
        <template v-if="menu==0">
            <h2>Contenido del menu = 0</h2>
        </template>

        <template v-if="menu==1">
            <category></category>
            {{-- nuestro componente category app.js --}}
        </template>

        <template v-if="menu==2">
            <item></item>
            {{-- nuestro componente articulo llamado item app.js --}}
        </template>

        <template v-if="menu==3">
            <h2>Contenido de menu Compras/Ingresos</h2>
        </template>

        <template v-if="menu==4">
            <h2>Contenido de menu Compras/proveedores</h2>
        </template>

        <template v-if="menu==5">
            <h2>Contenido de menu Ventas/ventas</h2>
        </template>

        <template v-if="menu==6">
            <client></client>
        </template>

        <template v-if="menu==7">
            <h2>Contenido de menu Acceso/usuarios</h2>
        </template>

        <template v-if="menu==8">
            <h2>Contenido de menu Acceso/roles</h2>
        </template>

        <template v-if="menu==9">
            <h2>Contenido de menu Reportes/Reporte ingresos</h2>
        </template>

        <template v-if="menu==10">
            <h2>Contenido de menu Reportes/Reporte ventas</h2>
        </template>

        <template v-if="menu==11">
            <h2>Contenido de menu Ayuda</h2>
        </template>

        <template v-if="menu==12">
            <h2>Contenido de menu Acerca de</h2>
        </template>

    @endsection