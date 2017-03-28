@echo off
C:
cd "C:\Program Files (x86)\Natural Docs"
PERL NaturalDocs --rebuild --input "C:\Users\mindplay.dk\Web\mootree-dev\dist" --output HTML "C:\Users\mindplay.dk\Web\mootree-dev\docs" --project "C:\Users\mindplay.dk\Web\mootree-dev\docs\project" --exclude-input "C:\Users\mindplay.dk\Web\mootree-dev\dist\mootools"
PAUSE
