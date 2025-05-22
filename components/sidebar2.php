<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

?>


<div class="flex flex-col h-full z-50 p-3 w-64 bg-[#4a628a] text-gray-100">
    <div class="space-y-3 mt-20">
        <div class="flex items-center justify-center">
            <!-- <h2>Dashboard</h2>
            <button class="p-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5 fill-current text-gray-100">
                    <rect width="352" height="32" x="80" y="96"></rect>
                    <rect width="352" height="32" x="80" y="240"></rect>
                    <rect width="352" height="32" x="80" y="384"></rect>
                </svg>
            </button> -->
            <img src="/Logistics/public/imgs/logo.png" alt="logo" class="size-40">
        </div>

        <div class="flex-1">
            <ul class="pt-2 pb-4 space-y-1 text-sm">
                <li class="rounded-xl  bg-gray-800 text-gray-50">
                    <a rel="noopener noreferrer" href="/Logistics/src/users/admin/project-management.php" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="size-8 fill-current text-white">
                            <path fill="currentColor" d="M20.894 17.553a1 1 0 0 1-.447 1.341l-8 4a1 1 0 0 1-.894 0l-8-4a1 1 0 0 1 .894-1.788L12 20.88l7.554-3.775a1 1 0 0 1 1.341.447m0-4a1 1 0 0 1-.447 1.341l-8 4a1 1 0 0 1-.894 0l-8-4a1 1 0 0 1 .894-1.788L12 16.88l7.554-3.775a1 1 0 0 1 1.341.447m0-4a1 1 0 0 1-.447 1.341l-8 4a1 1 0 0 1-.894 0l-8-4a1 1 0 0 1 .894-1.788L12 12.88l7.554-3.775a1 1 0 0 1 1.341.447M12.008 1q.056 0 .111.007l.111.02l.086.024l.012.006l.012.002l.029.014l.05.019l.016.009l.012.005l8 4a1 1 0 0 1 0 1.788l-8 4a1 1 0 0 1-.894 0l-8-4a1 1 0 0 1 0-1.788l8-4l.011-.005l.018-.01l.078-.032l.011-.002l.013-.006l.086-.024l.11-.02l.056-.005z" />
                        </svg>
                        <span>Project Management</span>
                    </a>
                </li>
                <li class="rounded-sm">
                    <a rel="noopener noreferrer" href="/Logistics/src/users/admin/procurement-management.php" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-8 fill-current text-white" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 15v6H5v-6m11 2H8m7.913-2.337L8.087 13m8.626-.62L9.463 9m8.71 1.642L12.044 5.5m7.99 3.304L15.109 2.5" />
                        </svg>
                        <span>Procurement</span>
                    </a>
                </li>
                <li class="rounded-sm">
                    <a rel="noopener noreferrer" href="/Logistics/src/users/admin/assets-management.php" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-8 fill-current text-white" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22 13.478V18a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-4.522l.553.277a21 21 0 0 0 18.897-.002zM14 2a3 3 0 0 1 3 3v1h2a3 3 0 0 1 3 3v2.242l-1.447.724a19 19 0 0 1-16.726.186l-.647-.32l-1.18-.59V9a3 3 0 0 1 3-3h2V5a3 3 0 0 1 3-3zm-2 8a1 1 0 0 0-1 1a1 1 0 1 0 2 .01c0-.562-.448-1.01-1-1.01m2-6h-4a1 1 0 0 0-1 1v1h6V5a1 1 0 0 0-1-1" />
                        </svg>
                        <span>Assets</span>
                    </a>
                </li>
                <li class="rounded-sm">
                    <a rel="noopener noreferrer" href="/Logistics/src/users/admin/warehouse-management.php" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-8 fill-current text-white" width="640" height="512" viewBox="0 0 640 512">
                            <path fill="currentColor" d="M0 488V171.3c0-26.2 15.9-49.7 40.2-59.4L308.1 4.8c7.6-3.1 16.1-3.1 23.8 0l267.9 107.1c24.3 9.7 40.2 33.3 40.2 59.4V488c0 13.3-10.7 24-24 24h-48c-13.3 0-24-10.7-24-24V224c0-17.7-14.3-32-32-32H128c-17.7 0-32 14.3-32 32v264c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24m488 24H152c-13.3 0-24-10.7-24-24v-56h384v56c0 13.3-10.7 24-24 24M128 400v-64h384v64zm0-96v-80h384v80z" />
                        </svg>
                        <span>Warehouse</span>
                    </a>
                </li>
                <li class="rounded-sm">
                    <a rel="noopener noreferrer" href="/Logistics/src/users/admin/mro-management.php" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg"  class="size-8 fill-current text-white" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M16.5 2a5.5 5.5 0 0 0-5.348 6.789L2.841 17.1a2.871 2.871 0 1 0 4.06 4.06l4.115-4.113a6.5 6.5 0 0 1 9.172-5.467A5.5 5.5 0 0 0 22 7.5a5.5 5.5 0 0 0-.282-1.745a.75.75 0 0 0-1.242-.292l-2.444 2.444a.75.75 0 0 1-1.06 0l-.879-.878a.75.75 0 0 1 0-1.06l2.445-2.445a.75.75 0 0 0-.293-1.241A5.5 5.5 0 0 0 16.5 2m-2.223 11.976a2 2 0 0 1-1.441 2.496l-.584.144a5.7 5.7 0 0 0 .006 1.808l.54.13a2 2 0 0 1 1.45 2.51l-.187.631c.44.386.94.699 1.485.922l.493-.519a2 2 0 0 1 2.899 0l.499.525a5.3 5.3 0 0 0 1.482-.913l-.198-.686a2 2 0 0 1 1.442-2.496l.583-.144a5.7 5.7 0 0 0-.006-1.808l-.54-.13a2 2 0 0 1-1.449-2.51l.186-.63a5.3 5.3 0 0 0-1.484-.922l-.493.518a2 2 0 0 1-2.9 0l-.498-.525c-.544.22-1.044.53-1.483.912zM17.5 19c-.8 0-1.45-.672-1.45-1.5S16.7 16 17.5 16s1.45.672 1.45 1.5S18.3 19 17.5 19"/></svg>
                        <span>MRO</span>
                    </a>
                </li>


            </ul>
        </div>
    </div>
    <div class="flex items-center absolute bottom-0 p-2 mt-12 space-x-4 justify-self-end">
        <div class="rounded-sm">
            <a rel="noopener noreferrer" href="#" class="flex items-center p-2 space-x-3 rounded-md">
               <svg xmlns="http://www.w3.org/2000/svg"  class="size-8 fill-current text-white" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M12 4a8 8 0 0 0-6.96 11.947A4.99 4.99 0 0 1 9 14h6a4.99 4.99 0 0 1 3.96 1.947A8 8 0 0 0 12 4m7.943 14.076q.188-.245.36-.502A9.96 9.96 0 0 0 22 12c0-5.523-4.477-10-10-10S2 6.477 2 12a9.96 9.96 0 0 0 2.057 6.076l-.005.018l.355.413A9.98 9.98 0 0 0 12 22q.324 0 .644-.02a9.95 9.95 0 0 0 5.031-1.745a10 10 0 0 0 1.918-1.728l.355-.413zM12 6a3 3 0 1 0 0 6a3 3 0 0 0 0-6" clip-rule="evenodd"/></svg>
                <span>Profile</span>
            </a>
        </div>
    </div>
</div>