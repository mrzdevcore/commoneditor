\NeedsTeXFormat{LaTeX2e}[1995/12/01]
\ProvidesClass{templates}[2014/12/15 v1.00]


\DeclareOption{12pt}{
	\PassOptionsToClass{\CurrentOption}{article}
}
\DeclareOption{fleqn}{
	\PassOptionsToClass{\CurrentOption}{article}
}

%% Custom package options
\DeclareOption{sansserif}{
	\PassOptionsToPackage{\CurrentOption}{paxcommands}
}
\DeclareOption{neverindent}{
	\PassOptionsToPackage{\CurrentOption}{paxcommands}
}

%% Fallback
\DeclareOption*{
	\ClassWarning{articleClinique}{Unknown option '\CurrentOption'}
}

%% Execute default options
\ExecuteOptions{12pt}

%% Process given options
\ProcessOptions\relax

\LoadClass[a4paper,12pt]{article}

\author{CommonLab}

\RequirePackage{geometry}

\geometry{a4paper,left=0.5cm,right=1cm,top=1cm,bottom=1.5cm}

\RequirePackage{artC}
\RequirePackage{lipsum}
%\RequirePackage[utf8]{inputenc}
%\RequirePackage[T1]{fontenc}
\usepackage[latin1]{inputenc}
\usepackage[T1]{fontenc}
\RequirePackage{lmodern}
\RequirePackage{graphicx}
\RequirePackage[frenchb]{babel}
\RequirePackage{xkeyval}
\RequirePackage{titlesec}
\RequirePackage{wasysym}
\RequirePackage{gensymb}
\RequirePackage{setspace}


\titleformat{\section}{\normalsize\bfseries}{\thesection}{0em}{}
\titleformat{\subsection}{\small\bfseries\itshape}{\thesubsection}{0em}{}
%\titlespacing{\section}{0pt}{0em}{0.1em}
%\titlespacing{\subsection}{0pt}{0em}{0.1em}

\newenvironment{Clinique}
{ \small  }
{ \par\noindent  \vskip 0.5em }


\endinput
