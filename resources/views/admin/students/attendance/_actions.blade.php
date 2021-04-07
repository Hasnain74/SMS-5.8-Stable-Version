<a class="open-modal" data-url="{{route('students.attendance.edit', $id)}}">
	<button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button>
</a>
<a class="open-modal" data-url="{{route('students.attendance.delete', $id)}}">
	<button title="View" class="btn btn-danger"><span class="fas fa-trash"></span></button>
</a>
<a class="open-modal" data-url="{{route('students.attendance.print', $id)}}">
	<button type="button" title="View" class="btn dusty-grass-gradient">
		<span class="fas fa-print"></span>
	</button>
</a>