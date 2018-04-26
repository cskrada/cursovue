@extends('principal')
@section('contenido')

    @if (Auth::check())
        @if (Auth::user()->idrole == 1)
            <template v-if="menu==0">
                <h2>Escritorio</h2>
            </template>
            <template v-if="menu==1">
                <category></category>
            </template>
            <template v-if="menu==2">
                <item></item>
            </template>
            <template v-if="menu==3">
                <h2>Contenido de menu Compras/Ingresos</h2>
            </template>
            <template v-if="menu==4">
                <provider></provider>
            </template>
            <template v-if="menu==5">
                <h2>Contenido de menu Ventas/ventas</h2>
            </template>
            <template v-if="menu==6">
                <client></client>
            </template>
            <template v-if="menu==7">
                <user></user>
            </template>
            <template v-if="menu==8">
                <role></role>
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

        @elseif (Auth::user()->idrole == 2)
            <template v-if="menu==5">
                <h2>Contenido de menu Ventas/ventas</h2>
            </template>

            <template v-if="menu==6">
                <client></client>
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

        @elseif (Auth::user()->idrole == 3)
            <template v-if="menu==1">
                <category></category>
            </template>
            <template v-if="menu==2">
                <item></item>
            </template>
            <template v-if="menu==3">
                <h2>Contenido de menu Compras/Ingresos</h2>
            </template>
            <template v-if="menu==4">
                <provider></provider>
            </template>
            <template v-if="menu==9">
                <h2>Contenido de menu Reportes/Reporte ingresos</h2>
            </template>
            <template v-if="menu==11">
                <h2>Contenido de menu Ayuda</h2>
            </template>

            <template v-if="menu==12">
                <h2>Contenido de menu Acerca de</h2>
            </template>
        @else 

        @endif
    @endif     
@endsection