\NeedsTeXFormat{LaTeX2e}[1995/12/01]
\ProvidesClass{templates}[2014/12/15 v1.00]


\DeclareOption{10pt}{
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
\ExecuteOptions{10pt}

%% Process given options
\ProcessOptions\relax

\LoadClass[a4paper,8pt]{article}

\author{CommonLab}

\RequirePackage{geometry}

\geometry{a4paper,left=1cm,right=0.5cm,top=0cm,bottom=0.5cm}

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


\titleformat{\section}{\small\bfseries}{\thesection}{0em}{}
\titleformat{\subsection}{\footnotesize\bfseries}{\thesubsection}{0em}{}
\titlespacing{\section}{0pt}{0em}{0.1em}
\titlespacing{\subsection}{0pt}{0em}{0.1em}

\newenvironment{Clinique}
{  \noindent \setlength{\parindent}{0pt}   \footnotesize }
{ \par\noindent  \vskip 0.1em }


\endinput
