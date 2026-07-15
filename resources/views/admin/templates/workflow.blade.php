@extends('layouts.admin')

@section('page_title','Workflow Approval')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-2xl shadow border border-slate-200 p-8">

        <h2 class="text-xl font-bold">

            {{ $template->title }}

        </h2>

        <p class="text-slate-500 text-sm mt-1">

            Atur urutan approval dokumen.

        </p>

        <form
            method="POST"
            action="{{ route('admin.templates.workflow.update',$template->id) }}">

            @csrf

            <div
                id="workflow-list"
                class="space-y-4 mt-8">

                @forelse($template->approvalWorkflow as $step)

                    <div class="flex gap-4">

                        <select
                            name="approver_user_id[]"
                            class="flex-1 rounded-xl border">

                            <option value="">

                                Tanpa User

                            </option>

                            @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                {{ $step->approver_user_id==$user->id ? 'selected':'' }}>

                                {{ $user->name }}

                                -

                                {{ $user->position }}

                            </option>

                            @endforeach

                        </select>

                        <button
                            type="button"
                            onclick="this.parentElement.remove()"
                            class="px-4 rounded-xl bg-red-100">

                            Hapus

                        </button>

                    </div>

                @empty

                @endforelse

            </div>

            <button
                type="button"
                id="addApproval"
                class="mt-5 px-4 py-2 rounded-xl bg-blue-100">

                + Tambah Approval

            </button>

            <div class="mt-8">

                <button
                    class="px-6 py-3 rounded-xl bg-blue-900 text-white">

                    Simpan Workflow

                </button>

            </div>

        </form>

    </div>

</div>

<script>

const users = `

<option value="">Pilih User</option>

@foreach($users as $user)

<option value="{{ $user->id }}">

{{ $user->name }} - {{ $user->position }}

</option>

@endforeach

`;

document.getElementById('addApproval')
.onclick=function(){

document.getElementById('workflow-list')
.insertAdjacentHTML(
'beforeend',

`<div class="flex gap-4">

<select
name="approver_user_id[]"
class="flex-1 rounded-xl border">

${users}

</select>

<button
type="button"
onclick="this.parentElement.remove()"
class="px-4 rounded-xl bg-red-100">

Hapus

</button>

</div>`

);

}

</script>

@endsection