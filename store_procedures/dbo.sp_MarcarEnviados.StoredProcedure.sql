USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_MarcarEnviados]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_MarcarEnviados]
      @Correlativo int
AS
BEGIN
      SET NOCOUNT OFF;
	if (select agrupar from EnvioCorreos EN
			inner join CorreosEstados ES on ES.estadoid = EN.CodCorreo
			where Correlativo = @Correlativo) = 0
		BEGIN -- Si no es AGrupar Actualiza normal
			UPDATE EnvioCorreos
			SET EnviaCorreo = 1, FechaEnvio= GETDATE()
			WHERE Correlativo = @Correlativo   
		END
	ELSE -- Sino revisa si es Espera Aprobacion o otro estado que tenga agrupar
		BEGIN
			if (select CodCorreo from EnvioCorreos EN
				where Correlativo = @Correlativo ) = 1 -- Espera Aprobacion
			BEGIN
				Update E
				SET EnviaCorreo = 1
					, FechaEnvio = GETDATE()
				From EnvioCorreos E
				inner join Contratos C on E.documentoid = C.idDocumento
				/*And E.CodCorreo = C.idEstado */ And E.CodCorreo = 1 and EnviaCorreo = 0
			END
			ELSE -- Otro estado con Agrupar
			BEGIN
				Update E
				SET EnviaCorreo = 1
				, FechaEnvio = GETDATE()
				From EnvioCorreos E
				where RutUsuario = (
							select RutUsuario from EnvioCorreos EN
							where Correlativo = @Correlativo
							AND EnviaCorreo = 0 
							AND EN.CodCorreo = E.CodCorreo
							)
			END
		END
END
GO
