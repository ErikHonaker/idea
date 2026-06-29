<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <p class="text-muted-foreground text-sm mt-2">Capture your thoughts. Make a plan.</p>

            <x-card 
                x-data
                @click="$dispatch('open-modal', 'create-idea')"
                is="button"
                type="button"
                data-test="create-idea-button"
                class="mt-10 cursor-pointer h-32 w-full text-left"
                >
                <p>What's the Idea?</p>
            </x-card>
        </header>
        <div
            x-data="{
                question: '',
                answer: '',
                error: '',
                loading: false,
                async ask() {
                    if (this.question.trim() === '' || this.loading) {
                        return;
                    }
                    this.loading = true;
                    this.answer = '';
                    this.error = '';
                    try {
                        const response = await fetch('{{ route('ask.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            },
                            body: JSON.stringify({ question: this.question}),
                        });
                        const data = await response.json();
                        if (!response.ok) {
                            this.error = data.error || 'Something went wrong.';
                            return;
                        }
                        this.answer = data.answer;
                    } catch (e) {
                        this.error = 'The assistant is unavailable right now.';
                    } finally {
                        this.loading = false;
                    }
                }
            }"
            class="mt-8"
        >
            <label for="ask" class="block mb-2">Have a question?</label>
            <textarea
                id="ask"
                x-model="question"
                @keydown.enter.prevent="ask()"
                :disabled="loading"
                rows="3"
                placeholder="Ask about the knowledge base..."
                class="w-full rounded-lg border border-input bg-background p-3 text-foreground"
            ></textarea>
            <button
                type="button"
                @click="ask()"
                :disabled="loading"
                class="btn mt-2"
            >
                <span x-show="!loading">Ask</span>
                <span x-show="loading">Thinking...</span>
            </button>

            <div x-show="answer" x-cloak class="mt-4">
                <x-card>
                    <p class="text-foreground whitespace-pre-line" x-text="answer"></p>
                </x-card>
            </div>
            <p x-show="error" x-cloak x-text="error" class="mt-4 text-sm text-red-500"></p>
        </div>

        <div>
            <a href="/ideas" class="btn {{ request()->has('status')? 'btn-outlined': '' }}">All</a>
            @foreach(App\IdeaStatus::cases() as $status)
                <a 
                    href="/ideas?status={{ $status->value }}" 
                    class="btn {{ request('status') === $status->value ? '': 'btn-outlined' }}"
                    >
                        {{  $status->label() }} <span class="text-xs pl-3">{{ $statusCounts->get($status->value) }}</span>
                    </a>
            @endforeach
            
        </div>

        <div class="mt-10 text-muted-foreground">
            <div class="grid md:grid-cols-2 gap-6">
                @forelse($ideas as $idea)
                    <x-card href="{{ route('ideas.show', $idea) }}">
                        @if($idea->image_path)
                            <div class="mb-4 -mx-4 -mt-4 rounded-t-lg overflow-hidden">
                                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="" class="w-full h-auto object-cover">
                            </div>
                        @endif
                        <h3 class="text-foreground text-lg">{{ $idea->title }}</h3>
                        <div class="mt-1">
                            <x-idea.status-label status="{{ $idea->status}}">
                                {{ $idea->status->label()}}
                            </x-idea.status-label>
                        </div>

                        <div class="mt-5 line-clamp-3">{{ $idea->description }}</div>
                        <div class="mt-4">{{ $idea->created_at->diffForHumans() }}</div>
                    </x-card>
                @empty
                    <x-card>
                        <p>No ideas at this time.</p>
                    </x-card>
                @endforelse
            </div>
        </div>
        <x-idea.modal />
    </div>
</x-layout>