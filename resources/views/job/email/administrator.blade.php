{{-- job.email.administrator --}}

<h4>Nuevo empleo pendiente de moderación</h4>

<p>La empresa <strong>{{ $job->recruiter->company }}</strong> de <strong>{{ $job->recruiter->user->username }}</strong> ha publicado una nueva oferta de empleo y está pendiente de ser aprobada.</p>

<p>Accede a la oferta haciendo clic <a href="{{ route('job.show', $job->id) }}">aquí</a>.</p>