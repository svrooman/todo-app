# Todo App

> A notch above _Hello World_ - Build out the classic Todo app with a Laravel stack.

## Requirements

Track your time as you work towards the MVP described below. There are no time constraints, spend as much or as
little time as you'd like - just make sure the requirements listed are met. The requirements have been kept pretty lean
so feel free to build past them, some ideas are noted at the end of this readme.

### Todos

- can be created by a user
- can be deleted by a user
- can be marked as completed by a user
- have the following fields:
  - title (required): short form single sentence
  - notes: longer form paragraphs
  - completion status
  - due date
- have views for listing, creating & editing

### Users

- a Todo belongs to a User -- Users should only be allowed to view their own Todos
- a User can have many Todos

### UI / UX

As a full stack developer, your ability to create a unique and fresh design is not being evaluated here. We've got
designers for that! However, your ability to create a smooth user experience is key.

Some things we really love:

- subtle animations and transitions
- clear and concise information architecture
- performant pages, supported with modern JS and CSS

We have the skeleton set up with Vue but if you feel that the best way to achieve the above is using server-rendered blade templates, great. If you want
to leverage Laravel as an API backend, and SPA the front end, that's cool too. Build out the app in the way that best displays your strengths.

## Tech Stack

- Laravel 5.8
- VueJS
  - _react or angular is cool too, but we use Vue for most projects_
- SCSS
  - _if you want to use a framework, we ❤️ Bootstrap 4 or TailwindCSS_
- MySQL or Sqlite for persistence

## Bonus Points

If you want to build past the basic requirements above, here's some areas to explore:

- attaching media to Todos
- exporting Todos to CSV
- reminders for upcoming Todos
- progressive web apps with offline support are pretty cool
- bulk editing for due date, completion status (maybe notes?)
