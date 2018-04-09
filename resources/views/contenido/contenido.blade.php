    @extends('principal')
    @section('contenido')
        <template v-if="menu==0">
            <example-component></example-component>
        </template>

        <template v-if="menu==1">
            <h2>Contenido de menu Almacen/categorias</h2>
        </template>

        <template v-if="menu==2">
            <h2>Contenido de menu Almacen/articulos</h2>
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
            <h2>Contenido de menu Ventas/clientes</h2>
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