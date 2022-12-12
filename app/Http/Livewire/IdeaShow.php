<?php

namespace App\Http\Livewire;

use App\Models\Idea;
use Livewire\Component;
use App\Http\Livewire\Traits\WithAuthRedirects;

class IdeaShow extends Component
{

    use WithAuthRedirects;

    public $idea;
    public $votesCount;

    protected $listeners = [
        'statusWasUpdated',
        'ideaWasUpdated',
        'ideaWasMarkedAsSpam',
        'ideaWasMarkedAsNotSpam',
        'commentWasAdded',
        'commentWasDeleted',
    ];

    public function mount(Idea $idea, $votesCount)
    {
        $this->idea = $idea;
        $this->votesCount = $votesCount;
        $this->hasVoted = $idea->isVotedByUser(auth()->user());
    }

    public function ideaWasMarkedAsSpam()
    {
        $this->idea->refresh();
    }

    public function ideaWasMarkedAsNotSpam()
    {
        $this->idea->refresh();
    }

    public function commentWasAdded()
    {
        $this->idea->refresh();
    }

    public function commentWasDeleted()
    {
        $this->idea->refresh();
    }

    public function vote()
    {
        if (auth()->guest()) {
            return $this->redirectToLogin();
        }

        if ($this->hasVoted) {
            $this->idea->removeVote(auth()->user());
            $this->votesCount--;
            $this->hasVoted = false;
        } else {
            $this->idea->vote(auth()->user());
            $this->votesCount++;
            $this->hasVoted = true;
        }

    }


    public function statusWasUpdated()
    {
        $this->idea->refresh();
    }


    public function ideaWasUpdated()
    {
        $this->idea->refresh();
    }


    public function render()
    {
        return view('livewire.idea-show');
    }
}
