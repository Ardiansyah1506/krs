@extends('layouts.app')


@section('content')

<div class="w-3/5 h-3/5 flex justify-center items-center flex-col gap-y-5">
    <h1 class="text-2xl font-bold">Apps Persiapan KRS</h1>
    <table class="w-full text-sm  text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 text-center uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Task
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Memangkirkan Status Akademik
                </th>

                <td class="px-6 py-4">
                    <div class="flex gap-x-4">
                        <a href="{{ Route ('updatemangkir')}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Update</a>
                        <a href="{{ Route ('restoremangkir')}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Restore</a>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Rubah Status Akademik base on pembayaran
                </th>

                <td class="px-6 py-4">
                    <div class="flex gap-x-4 items-center">
                        <a href="{{ Route('baseonpembayaran') }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Update</a>
                        <a href="{{ Route('restorebaseonpembayaran') }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Restore</a>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Rubah Status Akademik base on cuti
                </th>

                <td class="px-6 py-4">
                    <div class="flex gap-x-4">
                        <a href="{{ Route('changestatuscuti') }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Update</a>
                        <a href="{{ Route('restoredatacuti') }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Restore</a>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Pindah table temp ke aktive
                </th>

                <td class="px-6 py-4">
                    <div class="flex gap-x-4">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Update</a>
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Restore</a>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Pindah table temp ke archive
                </th>

                <td class="px-6 py-4">
                    <div class="flex gap-x-4">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Update</a>
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Restore</a>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Pindah presensi
                </th>

                <td class="px-6 py-4">
                    <div class="flex gap-x-4">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Update</a>
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Restore</a>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Pindah Nilai
                </th>

                <td class="px-6 py-4">
                    <div class="flex gap-x-4">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Update</a>
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Restore</a>
                    </div>
                </td>
            </tr>

        </tbody>
    </table>
</div>


@endsection
