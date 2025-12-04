# Guidelines to follow (or not)

1. git clone (repo-url) to clone the repository to your desktop

```bash
    git clone https://github.com/hevprog/DBMS_final.git
```

2. If you have not set up your name and email:
```bash
    git config user.name "Your Name"
    git config user.email "you@example.com"
```

3. Before making changes make sure your 'main' is updated
```bash
    git switch main
    git pull origin main
```

4. After updating main, you can create a new branch to start working.
```bash
    git switch -c feature/name  
```

5. After making changes, you can stage all changes and commit them.
```bash
    git status # Check what changed
    git add .
    git commit -m "feat: message"
```

6. You can now push your branch to the github repo
```bash
    git push -u origin feature/name  
```

7.  Then create a Pull Request on GitHub from feature/name into main. Pick reviewers, write description, link issue if any.


## you can read more GIT commands [here](https://education.github.com/git-cheat-sheet-education.pdf) or [an interactive one here](https://philomatics.com/git-cheatsheet-release)