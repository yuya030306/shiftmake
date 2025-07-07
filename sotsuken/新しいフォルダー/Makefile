FILENAME = main

TEX = platex
BIB = pbibtex
DVI = dvipdfmx

pdf:	clean
		$(MAKE) tex
		$(MAKE) bib
		$(MAKE) tex
		$(MAKE) tex
		$(MAKE) dvi

tex:
		$(TEX) -interaction=nonstopmode $(FILENAME)
bib:
		$(BIB) $(FILENAME)
dvi:
		$(DVI) $(FILENAME)

clean:
		-rm -rf *~ *.toc *.aux *.dvi *.log *.fls *.bbl *.blg *_latexmk
